<?php

use Faker\Generator as Faker;
use App\KudosTag;
use App\User;

$factory->define(KudosTag::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'author_id' => factory(User::class)->create()->id,
    ];
});
