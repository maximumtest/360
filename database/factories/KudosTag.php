<?php

use Faker\Generator as Faker;
use App\KudosTag;

$factory->define(KudosTag::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
