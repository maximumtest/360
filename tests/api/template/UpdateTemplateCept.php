<?php

use App\Role;
use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\Question;
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

$I->sendPATCH(route('v1.templates.update', ['template' => $faker->word]));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$template = factory(Template::class)->create();

$newTemplateName = $faker->text(20);
$questionId1 = factory(Question::class)->create()->id;
$questionId2 = factory(Question::class)->create()->id;

$I->sendPATCH(route('v1.templates.update', ['template' => $template->_id]), [
    'name' => $newTemplateName,
    'questions' => [$questionId1, $questionId1],
]);
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

$I->sendPATCH(route('v1.templates.update', ['template' => $template->_id]), [
    'name' => $newTemplateName,
    'questions' => [$questionId1, $questionId2],
]);
$I->seeResponseCodeIs(HttpCode::OK);
