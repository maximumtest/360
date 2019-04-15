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

$I->sendPATCH(route('v1.kudos-tags.update', ['kudos_tag' => $faker->word]));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$kudosTag = factory(KudosTag::class)->create([
    'name' => $faker->word,
]);

$newKudosTagName = $faker->word;

$I->sendPATCH(route('v1.kudos-tags.update', ['kudos_tag' => $kudosTag->_id]), [
    'name' => $newKudosTagName,
]);
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson([
    'name' => $newKudosTagName,
]);
