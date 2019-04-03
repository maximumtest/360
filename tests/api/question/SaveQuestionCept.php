<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use Faker\Factory;
use App\QuestionType;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$I->sendPOST(route('v1.questions.store'));
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

$questionTypeId = factory(QuestionType::class)->create(['name' => QuestionType::TYPE_SELECT])->id;
$questionText = $faker->text(30);
$answer1 = $faker->word;
$answer2 = $faker->word;

$I->sendPOST(route('v1.questions.store'), [
    'question_type_id' => $questionTypeId,
    'text' => $questionText,
]);
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

$I->sendPOST(route('v1.questions.store'), [
    'question_type_id' => $questionTypeId,
    'text' => $questionText,
    'answers' => [$answer1, $answer1],
]);
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

$I->sendPOST(route('v1.questions.store'), [
    'question_type_id' => $questionTypeId,
    'text' => $questionText,
    'answers' => [$answer1, $answer2],
]);
$I->seeResponseCodeIs(HttpCode::CREATED);
