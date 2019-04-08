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

$I->sendGET(route('v1.kudos-tags.index'));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$kudosTags = factory(KudosTag::class, 3)->create();

$I->sendGET(route('v1.kudos-tags.index'));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson($kudosTags->toArray());
