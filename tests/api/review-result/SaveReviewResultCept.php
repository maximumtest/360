<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\Review;
use App\Template;
use App\Question;

$I = new ApiTester($scenario);

// Check that we cannot save review result without token
$I->sendPOST(route('v1.review-results.store'));
$I->seeResponseIsJson();
$I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);

$interviewer = factory(User::class)->create();
$token = $I->getToken($interviewer->email, 123);
$I->amBearerAuthenticated($token);

// Check that we cannot save result without necessary fields
$I->sendPOST(route('v1.review-results.store'));
$I->seeResponseIsJson();
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
$I->seeResponseMatchesJsonType([
    'message' => 'String',
    'errors' => 'Array',
]);

// Check that we cannot save review result on yourself
$review = factory(Review::class)->create();
$template = Template::findOrFail($review->template_id);
$respondent = factory(User::class)->create();
$review->users()->sync([$interviewer->id, $respondent->id]);

$question1 = factory(Question::class)->create();
$question2 = factory(Question::class)->create();
$question3 = factory(Question::class)->create();

$template->questions()->sync($question1->id, $question2->id, $question3->id);

$reviewResult = [
    'review_id' => $review->id,
    'answers' => [
        [
            'question_id' => $question1->id,
            'answer' => 'here is some answer',
        ],
        [
            'question_id' => $question2->id,
            'answer' => 'here is another answer',
        ],
    ],
    'respondent_id' => $interviewer->id,
    'interviewer_id' => $interviewer->id,
];

$I->sendPOST(route('v1.review-results.store'), $reviewResult);
$I->seeResponseIsJson();
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
$I->seeResponseMatchesJsonType([
    'message' => 'String',
    'errors' => [
        'respondent_id' => 'Array',
    ],
]);

// Check that we cannot save review result with questions not included in template
$reviewResult = [
    'review_id' => $review->id,
    'answers' => [
        [
            'question_id' => 'notValidId',
            'answer' => 'here is some answer',
        ],
    ],
    'respondent_id' => $respondent->id,
    'interviewer_id' => $interviewer->id,
];

$I->sendPOST(route('v1.review-results.store'), $reviewResult);
$I->seeResponseIsJson();
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
$I->seeResponseMatchesJsonType([
    'message' => 'String',
    'errors' => [
        'answers.0.question_id' => 'Array',
    ],
]);

// Check that we can save valid review result
$reviewResult = [
    'review_id' => $review->id,
    'answers' => [
        [
            'question_id' => $question1->id,
            'answer' => 'here is some answer',
        ],
        [
            'question_id' => $question2->id,
            'answer' => 'here is another answer',
        ],
    ],
    'respondent_id' => $respondent->id,
    'interviewer_id' => $interviewer->id,
];

$I->sendPOST(route('v1.review-results.store'), $reviewResult);
$I->seeResponseIsJson();
$I->seeResponseCodeIs(HttpCode::CREATED);
