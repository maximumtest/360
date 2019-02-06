<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use Faker\Factory;
use App\User;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$token = $I->getToken($user->email, 'secret');
$I->amBearerAuthenticated($token);

$title = $faker->text(20);

$I->sendPOST(route('v1.templates.store', [
    'title' => $title,
]));

$I->seeResponseCodeIs(HttpCode::CREATED);
$I->seeResponseIsJson();
$I->canSeeResponseContainsJson([
    'title' => $title,
]);
