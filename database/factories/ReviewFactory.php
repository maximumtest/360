<?php

use Faker\Generator as Faker;
use App\Review;
use App\User;
use App\Template;

$factory->define(Review::class, function (Faker $faker) {
    return [
        'author_id' => factory(User::class)->create()->id,
        'template_id' => factory(Template::class)->create()->id,
        'title' => $faker->text(20),
    ];
});
