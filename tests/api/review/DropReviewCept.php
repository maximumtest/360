<?php

use App\Role;
use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\Review;
use Faker\Factory;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$managerRole = factory(Role::class)->create([
    'name' => Role::ROLE_MANAGER
]);
$user->assignRole($managerRole);

$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$I->sendDELETE(route('v1.reviews.destroy', ['review' => $faker->word]));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$review = factory(Review::class)->create();

$I->sendDELETE(route('v1.reviews.destroy', ['review' => $review->_id]));
$I->seeResponseCodeIs(HttpCode::NO_CONTENT);
