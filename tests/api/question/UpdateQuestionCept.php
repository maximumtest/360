<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\Question;
use App\QuestionType;
use Faker\Factory;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$I->sendPATCH(route('v1.questions.update', ['question' => $faker->word]));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$question = factory(Question::class)->create([
    'question_type_id' => QuestionType::TYPE_TEXT,
]);

$newQuestionText = $faker->text(20);
$newQuestionTypeId = factory(QuestionType::class)->create(['name' => QuestionType::TYPE_SELECT])->id;
$answer1 = $faker->word;
$answer2 = $faker->word;

$I->sendPATCH(route('v1.questions.update', ['question' => $question->_id]), [
    'question_type_id' => $newQuestionTypeId,
    'text' => $newQuestionText,
]);
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

$I->sendPATCH(route('v1.questions.update', ['question' => $question->_id]), [
    'question_type_id' => $newQuestionTypeId,
    'text' => $newQuestionText,
    'answers' => [$answer2, $answer2],
]);
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

$I->sendPATCH(route('v1.questions.update', ['question' => $question->_id]), [
    'question_type_id' => $newQuestionTypeId,
    'text' => $newQuestionText,
    'answers' => [$answer1, $answer2],
]);
$I->seeResponseCodeIs(HttpCode::OK);
