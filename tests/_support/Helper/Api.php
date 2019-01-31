<?php
namespace Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Artisan;
use Codeception\Lib\ModuleContainer;
use Codeception\Module\Laravel5;
use DatabaseSeeder;
use Illuminate\Foundation\Console\Kernel;
use Codeception\Lib\Connector\Laravel5 as LaravelConnector;
use Codeception\Lib\Driver\MongoDb as MongoDbDriver;
use Codeception\Exception\ModuleException;
use Codeception\Exception\ModuleConfigException;

class Api extends Laravel5
{
    public static $configurationApp = null;

    /**
     * @var \Codeception\Lib\Driver\MongoDb
     */
    public $driver = null;

    /**
     * **HOOK** executed before suite
     *
     * @param array $settings
     * @return mixed
     */
    public function _beforeSuite($settings = [])
    {
        if (is_null(self::$configurationApp)) {

            // Загружаем ларавел
            $app = require __DIR__ . '/../../../bootstrap/app.php';

            $app->loadEnvironmentFrom('.env.testing');

            $app->make(Kernel::class)->bootstrap();

            // Выполняем нужные команды
            Artisan::call('db:seed', ["--class" => DatabaseSeeder::class]);

            self::$configurationApp = $app;
        }

        return self::$configurationApp;
    }

    public function _before(\Codeception\TestInterface $test)
    {
        $this->client = new LaravelConnector($this);

        // Database migrations should run before database cleanup transaction starts
        if ($this->config['run_database_migrations']) {
            $this->callArtisan('migrate', ['--path' => $this->config['database_migrations_path']]);
        }

        if ($this->config['cleanup']) {
            $this->cleanup();
        }

        if ($this->config['run_database_seeder']) {
            $this->callArtisan('db:seed', ['--class' => $this->config['database_seeder_class']]);
        }
    }

    protected function cleanup()
    {
        if (!$this->driver) {
            $mongoDbDsn = 'mongodb://' . env('DB_HOST') . ':' . env('DB_PORT') . '/' . env('DB_DATABASE');
            $mongoDbUser = env('DB_USERNAME');
            $mongoDbPassword = env('DB_PASSWORD');
            $this->driver = MongoDbDriver::create($mongoDbDsn, $mongoDbUser, $mongoDbPassword);
        }

        $dbh = $this->driver->getDbh();
        if (!$dbh) {
            throw new ModuleConfigException(
                __CLASS__,
                "No connection to database. Remove this module from config if you don't need database repopulation"
            );
        }
        try {
            $this->driver->cleanup();
        } catch (\Exception $e) {
            throw new ModuleException(__CLASS__, $e->getMessage());
        }
    }
}
