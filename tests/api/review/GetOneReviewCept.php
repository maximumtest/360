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

$I->sendGET(route('v1.reviews.show', ['review' => $faker->word]));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$review = factory(Review::class)->create();

$I->sendGET(route('v1.reviews.show', ['review' => $review->_id]));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson($review->toArray());
