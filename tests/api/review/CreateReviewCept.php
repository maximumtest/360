<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use Faker\Factory;
use App\User;
use App\Template;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$token = $I->getToken($user->email, 'secret');
$I->amBearerAuthenticated($token);

$templateId = factory(Template::class)->create()->id;
$title = $faker->text(20);

$I->sendPOST(route('v1.reviews.store', [
    'template_id' => $templateId,
    'title' => $title,
]));

$I->seeResponseCodeIs(HttpCode::CREATED);
$I->seeResponseIsJson();
$I->canSeeResponseContainsJson([
    'template_id' => $templateId,
    'title' => $title,
]);
