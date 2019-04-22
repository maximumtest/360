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
$manager = factory(User::class)->create();
$managerRole = factory(\App\Role::class)->create(['name' => \App\Role::ROLE_MANAGER]);
$manager->assignRole($managerRole);

$token = $I->getToken($manager->email, 123);
$I->amBearerAuthenticated($token);

$user1 = factory(User::class)->create();
$user2 = factory(User::class)->create();
$employeeRole = factory(\App\Role::class)->create(['name' => \App\Role::ROLE_EMPLOYEE]);
$user1->assignRole($employeeRole);

$review = factory(Review::class)->create([
    'manager_id' => $manager->id,
]);
$review->users()->sync([$user1->id, $user2->id]);

$question1 = factory(Question::class)->create();
$question2 = factory(Question::class)->create();
$template = Template::findOrFail($review->template_id);
$template->questions()->sync([$question1->id, $question2->id]);

$reviewResult = factory(ReviewResult::class)->create([
    'review_id' => $review->id,
    'interviewer_id' => $user1->id,
    'respondent_id' => $user2->id,
]);

$token = $I->getToken($user2->email, 123);
$I->amBearerAuthenticated($token);

// Check that we cannot drop review result of other person
$I->sendDELETE(route('v1.review-results.destroy', ['review_result' => $reviewResult->id]));
$I->seeResponseCodeIs(HttpCode::FORBIDDEN);
$I->seeResponseIsJson();

// Check that we can drop own review-result
$token = $I->getToken($user1->email, 123);
$I->amBearerAuthenticated($token);

$I->sendDELETE(route('v1.review-results.destroy', ['review_result' => $reviewResult->id]));
$I->seeResponseCodeIs(HttpCode::NO_CONTENT);

// Check that manager can drop review
$token = $I->getToken($manager->email, 123);
$I->amBearerAuthenticated($token);

$reviewResult = factory(ReviewResult::class)->create([
    'review_id' => $review->id,
    'respondent_id' => $user2->id,
    'interviewer_id' => $user1->id,
]);

$I->sendDELETE(route('v1.review-results.destroy', ['review_result' => $reviewResult->id]));
$I->seeResponseCodeIs(HttpCode::NO_CONTENT);
