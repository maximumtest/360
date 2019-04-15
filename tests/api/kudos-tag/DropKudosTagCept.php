<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\KudosTag;
use Faker\Factory;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$I->sendDELETE(route('v1.kudos-tags.destroy', ['kudos_tag' => $faker->word]));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$kudosTag = factory(KudosTag::class)->create();

$I->sendDELETE(route('v1.kudos-tags.destroy', ['kudos_tag' => $kudosTag->_id]));
$I->seeResponseCodeIs(HttpCode::NO_CONTENT);
