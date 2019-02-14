<?php

use Faker\Generator as Faker;
use App\UserCode;

$factory->define(UserCode::class, function (Faker $faker) {
    return [
        'user_id' => $faker->unique()->uuid,
        'code' => UserCode::generateCode(),
    ];
});
