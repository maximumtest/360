<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\Template;
use Faker\Factory;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$I->sendGET(route('v1.templates.show', ['template' => $faker->word]));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$template = factory(Template::class)->create();

$I->sendGET(route('v1.templates.show', ['templates' => $template->_id]));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson($template->toArray());
