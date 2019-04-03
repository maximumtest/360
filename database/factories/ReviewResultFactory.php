<?php

use Faker\Generator as Faker;
use App\ReviewResult;
use App\Review;
use App\User;

$factory->define(ReviewResult::class, function (Faker $faker) {
    return [
        'review_id' => factory(Review::class)->create()->id,
        'respondent_id' => factory(User::class)->create()->id,
        'interviewer_id' => factory(User::class)->create()->id,
        'answers' => [],
    ];
});
