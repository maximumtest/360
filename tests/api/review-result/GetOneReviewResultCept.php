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

//Check that a user can see review result
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

$I->sendGET(route('v1.review-results.show', ['id' => $reviewResult1->id]));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson($reviewResult1->toArray());
$I->dontSeeResponseContainsJson($reviewResult2->toArray());

// Check that another user cannot see review result
$token = $I->getToken($user1->email, 123);
$I->amBearerAuthenticated($token);

$I->sendGET(route('v1.review-results.show', ['id' => $reviewResult1->id]));
$I->seeResponseCodeIs(HttpCode::FORBIDDEN);
$I->seeResponseIsJson();

//Check that manager don't see review result from other reviews
$manager = factory(User::class)->create();
$managerRole = Role::create(['name' => Role::ROLE_MANAGER]);
$manager->assignRole($managerRole);
$token = $I->getToken($manager->email, 123);
$I->amBearerAuthenticated($token);

$I->sendGET(route('v1.review-results.show', ['id' => $reviewResult1->id]));
$I->seeResponseCodeIs(HttpCode::FORBIDDEN);
$I->seeResponseIsJson();

// Check that admin can see review results
$admin = factory(User::class)->create();
$adminRole = Role::create(['name' => Role::ROLE_ADMIN]);
$admin->assignRole($adminRole);
$token = $I->getToken($admin->email, 123);
$I->amBearerAuthenticated($token);

$I->sendGET(route('v1.review-results.show', ['id' => $reviewResult1->id]));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson($reviewResult1->toArray());
$I->dontSeeResponseContainsJson($reviewResult2->toArray());
