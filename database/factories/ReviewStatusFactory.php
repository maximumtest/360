<?php

use Faker\Generator as Faker;
use App\ReviewStatus;

$factory->define(ReviewStatus::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement([
            ReviewStatus::STATUS_DRAFT,
            ReviewStatus::STATUS_IN_PROGRESS,
            ReviewStatus::STATUS_PAUSED,
            ReviewStatus::STATUS_FINISHED,
        ]),
    ];
});
