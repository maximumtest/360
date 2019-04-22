<?php

use Illuminate\Database\Seeder;
use App\KudosTag;

class KudosTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(KudosTag::class, 3)->create();
    }
}
