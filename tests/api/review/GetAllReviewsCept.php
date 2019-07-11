<?php

use App\Role;
use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use Faker\Factory;
use App\Review;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$managerRole = factory(Role::class)->create([
    'name' => Role::ROLE_MANAGER
]);
$user->assignRole($managerRole);

$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$I->sendGET(route('v1.reviews.index'));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$reviews = factory(Review::class, 3)->create();

$I->sendGET(route('v1.reviews.index'));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson($reviews->toArray());
