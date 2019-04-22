<?php

use Faker\Generator as Faker;
use App\KudosCategory;

$factory->define(KudosCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
