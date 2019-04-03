<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use Faker\Factory;
use App\Template;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$I->sendDELETE(route('v1.templates.destroy', ['template' => $faker->word]));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$template = factory(Template::class)->create();

$I->sendDELETE(route('v1.templates.destroy', ['template' => $template->_id]));
$I->seeResponseCodeIs(HttpCode::NO_CONTENT);
