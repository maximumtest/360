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

$I->sendPOST(route('v1.kudos-tags.store'));
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

$kudosTagName = $faker->word;

$I->sendPOST(route('v1.kudos-tags.store'), [
    'name' => $kudosTagName,
]);
$I->seeResponseCodeIs(HttpCode::CREATED);
$I->seeResponseContainsJson([
    'name' => $kudosTagName,
]);
