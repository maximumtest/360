<?php

use Faker\Generator as Faker;
use App\QuestionType;

$factory->define(QuestionType::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement([
            QuestionType::TYPE_RADIO,
            QuestionType::TYPE_CHECKBOX,
            QuestionType::TYPE_SELECT,
            QuestionType::TYPE_TEXT,
            QuestionType::TYPE_TEXTAREA,
        ]),
    ];
});
