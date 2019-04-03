<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\Question;
use Faker\Factory;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$I->sendGET(route('v1.questions.filter'), [
    'text' => $faker->word,
]);
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$question1 = factory(Question::class)->create(['text' => 'Вопрос ' . $faker->word]);
$question2 = factory(Question::class)->create(['text' => 'Вопрос ' . $faker->word]);
$question3 = factory(Question::class)->create(['text' => 'Question ' . $faker->word]);

$I->sendGET(route('v1.questions.filter'), [
    'text' => 'Вопрос'
]);
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseContainsJson([
    $question1->toArray(),
    $question2->toArray(),
]);
$I->dontSeeResponseContainsJson([
    $question3->toArray(),
]);
