<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use Faker\Factory;
use App\Kudos;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$respondent = factory(User::class)->create();

$I->sendGET(route('v1.kudos.show', [
    'user_to' => $respondent->id,
    'kudos' => $faker->word,
]));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$kudos = factory(Kudos::class)->create();

$I->sendGET(route('v1.kudos.show', [
    'user_to' => $respondent->id,
    'kudos' => $kudos->id,
]));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson($kudos->toArray());
