<?php

use Illuminate\Database\Seeder;
use App\ReviewStatus;

class ReviewStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReviewStatus::firstOrCreate(['name' => ReviewStatus::STATUS_DRAFT]);
        ReviewStatus::firstOrCreate(['name' => ReviewStatus::STATUS_FINISHED]);
        ReviewStatus::firstOrCreate(['name' => ReviewStatus::STATUS_IN_PROGRESS]);
        ReviewStatus::firstOrCreate(['name' => ReviewStatus::STATUS_PAUSED]);
    }
}
