<?php

use Faker\Generator as Faker;
use App\Question;
use App\QuestionType;
use App\User;

$factory->define(Question::class, function (Faker $faker) {
    return [
        'question_type_id' => factory(QuestionType::class)->create()->id,
        'text' => $faker->text(30),
        'answers' => [$faker->word, $faker->word],
        'author_id' => factory(User::class)->create()->id,
    ];
});
