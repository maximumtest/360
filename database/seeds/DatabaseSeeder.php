<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            KudosCategorySeeder::class,
            KudosTagSeeder::class,
            QuestionTypeSeeder::class,
            QuestionSeeder::class,
            ReviewStatusSeeder::class,
            ReviewSeeder::class,
            ReviewResultSeeder::class,
            TemplateSeeder::class,
        ]);
    }
}
