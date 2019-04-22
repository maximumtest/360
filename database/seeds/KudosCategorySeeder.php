<?php

use Illuminate\Database\Seeder;
use App\KudosCategory;

class KudosCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(KudosCategory::class, 3)->create();
    }
}
