<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\KudosTag;
use Faker\Factory;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$I->sendGET(route('v1.kudos-tags.filter'), [
    'name' => $faker->word,
]);
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$kudosTag1 = factory(KudosTag::class)->create(['name' => 'Тег ' . $faker->word]);
$kudosTag2 = factory(KudosTag::class)->create(['name' => 'Тег ' . $faker->word]);
$kudosTag3 = factory(KudosTag::class)->create(['name' => 'Tag ' . $faker->word]);

$I->sendGET(route('v1.kudos-tags.filter'), [
    'name' => 'Тег'
]);
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson([
    $kudosTag1->toArray(),
    $kudosTag2->toArray(),
]);
$I->dontSeeResponseContainsJson([
    $kudosTag3->toArray(),
]);
