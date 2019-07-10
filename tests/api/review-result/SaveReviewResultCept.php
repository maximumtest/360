<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\Review;
use App\Template;
use App\Question;
use App\Role;

$I = new ApiTester($scenario);

// Check that we cannot save review result without token
$I->sendPOST(route('v1.review-results.store'));
$I->seeResponseIsJson();
$I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);

$interviewer = factory(User::class)->create();
$managerRole = factory(Role::class)->create(['name' => Role::ROLE_MANAGER]);
$interviewer->assignRole($managerRole);

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
$template = factory(Template::class)->create();
$question1 = factory(Question::class)->create();
$question2 = factory(Question::class)->create();
$question3 = factory(Question::class)->create();

$template->questions()->sync([$question1->id, $question2->id, $question3->id]);

$review = factory(Review::class)->create([
    'template_id' => $template->id,
]);

$respondent = factory(User::class)->create();
$review->users()->sync([$interviewer->id, $respondent->id]);
$employeeRole = factory(Role::class)->create(['name' => Role::ROLE_EMPLOYEE]);
$respondent->assignRole($employeeRole);

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
$token = $I->getToken($respondent->email, 123);
$I->amBearerAuthenticated($token);
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
        [
            'question_id' => $question3->id,
            'answer' => 'here is another answer',
        ],
    ],
    'respondent_id' => $interviewer->id,
    'interviewer_id' => $respondent->id,
];

$I->sendPOST(route('v1.review-results.store'), $reviewResult);
$I->seeResponseIsJson();
$I->seeResponseCodeIs(HttpCode::CREATED);
