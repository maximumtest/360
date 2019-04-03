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

$I->sendGET(route('v1.questions.index'));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$questions = factory(Question::class, 3)->create();

$I->sendGET(route('v1.questions.index'));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson($questions->toArray());
