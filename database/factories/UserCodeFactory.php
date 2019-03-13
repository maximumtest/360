<?php

use Faker\Generator as Faker;
use App\UserCode;

$factory->define(UserCode::class, function (Faker $faker) {
    return [
        'code' => UserCode::generateCode(),
    ];
});
