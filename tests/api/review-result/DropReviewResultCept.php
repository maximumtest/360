<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\Review;
use App\Template;
use App\Question;
use App\ReviewResult;

$I = new ApiTester($scenario);

// Check that we cannot drop review of other person
$interviewer = factory(User::class)->create();
$token = $I->getToken($interviewer->email, 123);
$I->amBearerAuthenticated($token);

$review = factory(Review::class)->create();
$user1 = factory(User::class)->create();
$respondent = factory(User::class)->create();
$review->users()->sync([$user1->id, $interviewer->id, $respondent->id]);

$question1 = factory(Question::class)->create();
$question2 = factory(Question::class)->create();
$template = Template::findOrFail($review->template_id);
$template->questions()->sync($question1->id, $question2->id);

$reviewResult = factory(ReviewResult::class)->create([
    'interviewer_id' => $user1->id,
    'respondent_id' => $interviewer->id,
]);

$I->sendDELETE(route('v1.review-results.destroy', ['review_result' => $reviewResult->id]));
$I->seeResponseIsJson();
$I->seeResponseCodeIs(HttpCode::FORBIDDEN);

// Check that we can drop valid review
$reviewResult = factory(ReviewResult::class)->create([
    'review_id' => $review->id,
    'respondent_id' => $respondent->id,
    'interviewer_id' => $interviewer->id,
    'answers' => [
        [
            'question_id' => $question1->id,
            'answer' => 'some answer',
        ],
        [
            'question_id' => $question2->id,
            'answer' => 'some answer',
        ],
    ],
]);
$I->sendDELETE(route('v1.review-results.destroy', ['review_result' => $reviewResult->id]));
$I->seeResponseIsJson();
$I->seeResponseCodeIs(HttpCode::NO_CONTENT);

// Check that manager can drop review
$manager = User::findOrFail($review->manager_id);
$token = $I->getToken($manager->email, 123);
$I->amBearerAuthenticated($token);

$reviewResult = factory(ReviewResult::class)->create([
    'review_id' => $review->id,
    'respondent_id' => $respondent->id,
    'interviewer_id' => $interviewer->id,
    'answers' => [
        [
            'question_id' => $question1->id,
            'answer' => 'some answer',
        ],
        [
            'question_id' => $question1->id,
            'answer' => 'some answer',
        ],
    ],
]);

$I->sendDELETE(route('v1.review-results.destroy', ['review_result' => $reviewResult->id]));
$I->seeResponseIsJson();
$I->seeResponseCodeIs(HttpCode::NO_CONTENT);