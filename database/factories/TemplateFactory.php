<?php

use Faker\Generator as Faker;
use App\User;
use App\Template;

$factory->define(Template::class, function (Faker $faker) {
    return [
        'name' => $faker->text(25),
        'author_id' => factory(User::class)->create()->id,
    ];
});
