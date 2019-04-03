<?php

use Faker\Generator as Faker;
use App\Review;
use App\User;
use App\Template;
use App\ReviewStatus;

$factory->define(Review::class, function (Faker $faker) {
    return [
        'manager_id' => factory(User::class)->create()->id,
        'template_id' => factory(Template::class)->create()->id,
        'title' => $faker->text(15),
        'review_status_id' => factory(ReviewStatus::class)->create()->id,
    ];
});
