<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\Question;
use Faker\Factory;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$I->sendDELETE(route('v1.questions.destroy', ['question' => $faker->word]));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$question = factory(Question::class)->create();

$I->sendDELETE(route('v1.questions.destroy', ['question' => $question->_id]));
$I->seeResponseCodeIs(HttpCode::NO_CONTENT);
