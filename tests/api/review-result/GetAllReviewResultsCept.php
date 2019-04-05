<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\Review;
use App\Template;
use App\Question;
use App\ReviewResult;
use App\Role;

$I = new ApiTester($scenario);

//Check that a user can see all review results
$interviewer = factory(User::class)->create();
$token = $I->getToken($interviewer->email, 123);
$I->amBearerAuthenticated($token);

$review = factory(Review::class)->create();
$respondent = factory(User::class)->create();
$user1 = factory(User::class)->create();
$user2 = factory(User::class)->create();
$review->users()->sync([$user1->id, $user2->id, $interviewer->id, $respondent->id]);

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

$reviewResult3 = factory(ReviewResult::class)->create([
    'review_id' => $review->id,
    'respondent_id' => $interviewer->id,
    'interviewer_id' => $user1->id,
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

$I->sendGET(route('v1.review-results.index'));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
    $reviewResult1->toArray(),
    $reviewResult2->toArray(),
]);
$I->dontSeeResponseContainsJson($reviewResult3->toArray());

//Check that manager see all review results from review but don't see review results from other reviews
$manager = factory(User::class)->create();
$manager->assignRole(Role::where('name', Role::ROLE_MANAGER));
$token = $I->getToken($manager->email, 123);
$I->amBearerAuthenticated($token);

$review2 = factory(Review::class)->create();
$review2->users()->sync([$user1->id, $user2->id, $interviewer->id, $respondent->id]);
$template = Template::findOrFail($review2->template_id);
$template->questions()->sync($question1->id, $question2->id);

$reviewResult4 = factory(ReviewResult::class)->create([
    'review_id' => $review2->id,
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

$reviewResult5 = factory(ReviewResult::class)->create([
    'review_id' => $review2->id,
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

$reviewResult6 = factory(ReviewResult::class)->create([
    'review_id' => $review2->id,
    'respondent_id' => $interviewer->id,
    'interviewer_id' => $user1->id,
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

$I->sendGET(route('v1.review-results.index'));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
    $reviewResult4->toArray(),
    $reviewResult5->toArray(),
    $reviewResult6->toArray(),
]);
$I->dontSeeResponseContainsJson([
    $reviewResult1->toArray(),
    $reviewResult2->toArray(),
    $reviewResult3->toArray(),
]);

// Check that admin see all review results
$admin = factory(User::class)->create();
$admin->assignRole(Role::where('name', Role::ROLE_ADMIN));
$token = $I->getToken($admin->email, 123);
$I->amBearerAuthenticated($token);

$I->sendGET(route('v1.review-results.index'));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
    $reviewResult1->toArray(),
    $reviewResult2->toArray(),
    $reviewResult3->toArray(),
    $reviewResult4->toArray(),
    $reviewResult5->toArray(),
    $reviewResult6->toArray(),
]);