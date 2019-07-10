<?php

use App\Role;
use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use Faker\Factory;
use App\Template;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$managerRole = factory(Role::class)->create([
    'name' => Role::ROLE_MANAGER
]);
$user->assignRole($managerRole);

$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$I->sendPOST(route('v1.reviews.store'));
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

$reviewTemplateId = factory(Template::class)->create()->id;
$reviewTitle = $faker->text(15);
$userId1 = factory(User::class)->create()->id;
$userId2 = factory(User::class)->create()->id;

$I->sendPOST(route('v1.reviews.store'), [
    'template_id' => $reviewTemplateId,
    'title' => $reviewTitle,
    'users' => [$userId1, $userId1],
]);
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

$I->sendPOST(route('v1.reviews.store'), [
    'template_id' => $reviewTemplateId,
    'title' => $reviewTitle,
    'users' => [$userId1, $userId2],
]);
$I->seeResponseCodeIs(HttpCode::CREATED);
