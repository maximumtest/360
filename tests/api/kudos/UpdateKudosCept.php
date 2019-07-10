<?php

use App\Role;
use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use Faker\Factory;
use App\KudosCategory;
use App\Kudos;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$employeeRole = factory(Role::class)->create([
    'name' => Role::ROLE_EMPLOYEE
]);
$user->assignRole($employeeRole);

$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$respondent = factory(User::class)->create();
$notOwnKudos = factory(Kudos::class)->create();
$ownKudos = factory(Kudos::class)->create([
    'user_from_id' => $user->id,
]);

$I->sendPATCH(route('v1.kudos.update', [
    'user_to' => $respondent->id,
    'kudos' => $faker->word,
]));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$I->sendPATCH(route('v1.kudos.update', [
    'user_to' => $respondent->id,
    'kudos' => $notOwnKudos->id,
]));
$I->seeResponseCodeIs(HttpCode::FORBIDDEN);

$I->sendPATCH(route('v1.kudos.update', [
    'user_to' => $respondent->id,
    'kudos' => $ownKudos->id,
]), [
    'kudos_category_id' => $faker->word,
]);
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

$updatedParams = [
    'kudos_category_id' => factory(KudosCategory::class)->create()->id,
    'text' => $faker->text(30),
];

$I->sendPATCH(route('v1.kudos.update', [
    'user_to' => $respondent->id,
    'kudos' => $ownKudos->id,
]), $updatedParams);
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson($updatedParams);
