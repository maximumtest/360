<?php

use App\Role;
use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use Faker\Factory;
use App\KudosCategory;

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
$kudosCategoryId = factory(KudosCategory::class)->create()->id;

$params = [
    'text' => $faker->text(25),
    'kudos_category_id' => $kudosCategoryId,
];

$tag1 = $faker->word;
$tag2 = $faker->word;

$I->sendPOST(route('v1.kudos.store', ['user_to' => $respondent->id]));
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

$I->sendPOST(route('v1.kudos.store', ['user_to' => $user->id]), $params);
$I->seeResponseCodeIs(HttpCode::FORBIDDEN);

$I->sendPOST(
    route('v1.kudos.store', ['user_to' => $respondent->id]),
    array_merge(
        $params,
        ['tags' => [$tag1, $tag2]]
    )
);
$I->seeResponseCodeIs(HttpCode::CREATED);
$I->seeResponseContainsJson($params);

$I->sendGET(route('v1.kudos-tags.index'));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson([
    ['name' => $tag1],
    ['name' => $tag2],
]);
