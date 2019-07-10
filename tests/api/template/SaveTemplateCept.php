<?php

use App\Role;
use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use Faker\Factory;
use App\Question;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$managerRole = factory(Role::class)->create([
    'name' => Role::ROLE_MANAGER
]);
$user->assignRole($managerRole);

$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$I->sendPOST(route('v1.templates.store'));
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

$templateName = $faker->text(15);
$questionId1 = factory(Question::class)->create()->id;
$questionId2 = factory(Question::class)->create()->id;

$I->sendPOST(route('v1.templates.store'), [
    'name' => $templateName,
    'questions' => [$questionId1, $questionId1],
]);
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

$I->sendPOST(route('v1.templates.store'), [
    'name' => $templateName,
    'questions' => [$questionId1, $questionId2],
]);
$I->seeResponseCodeIs(HttpCode::CREATED);
