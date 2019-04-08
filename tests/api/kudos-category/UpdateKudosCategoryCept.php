<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\KudosCategory;
use Faker\Factory;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$I->sendPATCH(route('v1.kudos-categories.update', ['kudos_category' => $faker->word]));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$kudosCategory = factory(KudosCategory::class)->create([
    'name' => $faker->word,
]);

$newKudosCategoryName = $faker->word;

$I->sendPATCH(route('v1.kudos-categories.update', ['kudos_category' => $kudosCategory->_id]), [
    'name' => $newKudosCategoryName,
]);
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson([
    'name' => $newKudosCategoryName,
]);
