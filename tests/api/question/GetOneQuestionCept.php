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

$I->sendGET(route('v1.questions.show', ['question' => $faker->word]));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$question = factory(Question::class)->create();

$I->sendGET(route('v1.questions.show', ['question' => $question->_id]));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson($question->toArray());
