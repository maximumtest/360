<?php

use Faker\Generator as Faker;
use App\{Kudos, User, KudosCategory};

$factory->define(Kudos::class, function (Faker $faker) {
    return [
        'text' => $faker->text(50),
        'user_from_id' => factory(User::class)->create()->id,
        'user_to_id' => factory(User::class)->create()->id,
        'kudos_category_id' => factory(KudosCategory::class)->create()->id,
    ];
});
