<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use Faker\Factory;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$I->sendPOST(route('v1.kudos-categories.store'));
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

$kudosCategoryName = $faker->word;

$I->sendPOST(route('v1.kudos-categories.store'), [
    'name' => $kudosCategoryName,
]);
$I->seeResponseCodeIs(HttpCode::CREATED);
$I->seeResponseContainsJson([
    'name' => $kudosCategoryName,
]);
