<?php

use App\Role;
use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\Review;
use App\Template;
use App\Question;
use App\ReviewResult;

$I = new ApiTester($scenario);

// Check that we can update own review
$interviewer = factory(User::class)->create();
$employeeRole = factory(Role::class)->create([
    'name' => Role::ROLE_EMPLOYEE
]);
$interviewer->assignRole($employeeRole);
$token = $I->getToken($interviewer->email, 123);
$I->amBearerAuthenticated($token);

$review = factory(Review::class)->create();
$respondent = factory(User::class)->create();
$user1 = factory(User::class)->create();
$user1->assignRole($employeeRole);
$review->users()->sync([$user1->id, $interviewer->id, $respondent->id]);

$question1 = factory(Question::class)->create();
$question2 = factory(Question::class)->create();
$template = Template::findOrFail($review->template_id);
$template->questions()->sync([$question1->id, $question2->id]);

$reviewResult1 = factory(ReviewResult::class)->create([
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

$reviewResult2 = factory(ReviewResult::class)->create([
    'review_id' => $review->id,
    'respondent_id' => $user1->id,
    'interviewer_id' => $interviewer->id,
    'answers' => [
        [
            'question_id' => $question1->id,
            'answer' => 'another answer',
        ],
        [
            'question_id' => $question2->id,
            'answer' => 'another answer',
        ],
    ],
]);

$validRequest = [
    'respondent_id' => $respondent->id,
    'interviewer_id' => $interviewer->id,
    'answers' => [
        [
            'question_id' => $question1->id,
            'answer' => 'I changed answer',
        ],
        [
            'question_id' => $question2->id,
            'answer' => 'I changed answer',
        ],
    ],
];

$I->sendPATCH(route('v1.review-results.update', ['id' => $reviewResult1->id]), $validRequest);
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson($validRequest);

// Check that we cannot update review result without necessary fields
$invalidRequest = [
    'review_id' => 123,
    'respondent_id' => true,
    'interviewer_id' => [],
    'answers' => [
        [
            'question_id' => 'wrongId',
        ],
    ],
];
$I->sendPATCH(route('v1.review-results.update', ['id' => $reviewResult1->id]), $invalidRequest);
$I->seeResponseIsJson();
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
$I->seeResponseMatchesJsonType([
    'message' => 'String',
    'errors' => 'Array',
]);

// Check that we cannot update review result on other person
$token = $I->getToken($user1->email, 123);
$I->amBearerAuthenticated($token);

$validRequest['interviewer_id'] = 1;
$I->sendPATCH(route('v1.review-results.update', ['id' => $reviewResult1->id]), $validRequest);
$I->seeResponseCodeIs(HttpCode::FORBIDDEN);
$I->seeResponseIsJson();
