<?php

namespace Helper;

use Codeception\Module\Laravel5;
use Codeception\Lib\Connector\Laravel5 as LaravelConnector;
use Codeception\Lib\Driver\MongoDb as MongoDbDriver;
use Codeception\Exception\ModuleException;
use Codeception\Exception\ModuleConfigException;

class Mongo extends Laravel5
{
    /**
     * @var \Codeception\Lib\Driver\MongoDb
     */
    public $driver = null;

    public function _before(\Codeception\TestInterface $test)
    {
        $this->client = new LaravelConnector($this);

        // Database migrations should run before database cleanup transaction starts
        if ($this->config['run_database_migrations']) {
            $this->callArtisan('migrate', ['--path' => $this->config['database_migrations_path']]);
        }

        if (!empty($this->app['config']['database.default']) && $this->config['cleanup']) {
            $this->debugSection('Database', 'Transaction started');
        }

        if ($this->config['run_database_seeder']) {
            $this->callArtisan('db:seed', ['--class' => $this->config['database_seeder_class']]);
        }
    }

    public function _after(\Codeception\TestInterface $test)
    {
        if (!empty($this->app['config']['database.default'])) {
            $db = $this->app['db'];

            if ($db instanceof \Illuminate\Database\DatabaseManager) {
                if ($this->config['cleanup']) {
                    $this->cleanup();
                    $this->debugSection('Database', 'Transaction cancelled; all changes reverted.');
                }

                /**
                 * Close all DB connections in order to prevent "Too many connections" issue
                 *
                 * @var \Illuminate\Database\Connection $connection
                 */
                foreach ($db->getConnections() as $connection) {
                    $connection->disconnect();
                }
            }

            // Remove references to Faker in factories to prevent memory leak
            unset($this->app[\Faker\Generator::class]);
            unset($this->app[\Illuminate\Database\Eloquent\Factory::class]);
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
