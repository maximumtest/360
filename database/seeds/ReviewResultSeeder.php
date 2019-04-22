<?php

use Illuminate\Database\Seeder;
use App\ReviewResult;

class ReviewResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ReviewResult::class, 3)->create();
    }
}
