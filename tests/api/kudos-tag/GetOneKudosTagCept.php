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

$I->sendGET(route('v1.kudos-tags.show', ['kudos_tag' => $faker->word]));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$kudosTag = factory(KudosTag::class)->create();

$I->sendGET(route('v1.kudos-tags.show', ['kudos_tag' => $kudosTag->_id]));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson($kudosTag->toArray());
