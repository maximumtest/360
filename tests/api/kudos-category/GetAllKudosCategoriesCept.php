<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\KudosCategory;
use Faker\Factory;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$I->sendGET(route('v1.kudos-categories.index'));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$kudosCategories = factory(KudosCategory::class, 3)->create();

$I->sendGET(route('v1.kudos-categories.index'));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson($kudosCategories->toArray());
