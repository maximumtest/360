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

$I->sendGET(route('v1.question-types.index'));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);
$I->seeResponseIsJson();

$questionTypes = factory(QuestionType::class, 3)->create();

$I->sendGET(route('v1.question-types.index'));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson($questionTypes->toArray());
