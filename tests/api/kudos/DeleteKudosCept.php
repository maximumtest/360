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
$notOwnKudos = factory(Kudos::class)->create();
$ownKudos = factory(Kudos::class)->create([
    'user_from_id' => $user->id,
]);

$I->sendDELETE(route('v1.kudos.destroy', [
    'user_to' => $respondent->id,
    'kudos' => $faker->word,
]));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$I->sendDELETE(route('v1.kudos.destroy', [
    'user_to' => $respondent->id,
    'kudos' => $notOwnKudos->id,
]));
$I->seeResponseCodeIs(HttpCode::FORBIDDEN);

$I->sendDELETE(route('v1.kudos.destroy', [
    'user_to' => $respondent->id,
    'kudos' => $ownKudos->id,
]));
$I->seeResponseCodeIs(HttpCode::NO_CONTENT);
