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

$I->sendGET(route('v1.users.filter'), [
    'searchTerm' => $faker->word,
]);
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$user1 = factory(User::class)->create(['name' => 'Пользователь ' . $faker->word]);
$user2 = factory(User::class)->create(['name' => 'Пользователь ' . $faker->word]);
$user3 = factory(User::class)->create(['name' => 'User ' . $faker->word]);

$I->sendGET(route('v1.users.filter'), [
    'searchTerm' => 'Пользователь',
]);
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson([
    $user1->toArray(),
    $user2->toArray(),
]);
$I->dontSeeResponseContainsJson([
    $user3->toArray(),
]);
