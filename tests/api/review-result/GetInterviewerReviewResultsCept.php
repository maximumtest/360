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

$interviewer = factory(User::class)->create();
$token = $I->getToken($interviewer->email, 123);
$I->amBearerAuthenticated($token);

$review = factory(Review::class)->create();
$respondent = factory(User::class)->create();
$review->users()->sync([$interviewer->id, $respondent->id]);

$question1 = factory(Question::class)->create();
$question2 = factory(Question::class)->create();
$template = Template::findOrFail($review->template_id);
$template->questions()->sync([$question1->id, $question2->id]);

$anotherInterviewer = factory(User::class)->create();

$I->sendGET(route('v1.review-results.get-interviewer-review-results', ['review_id' => $review->id]));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseIsJson();
$I->canSeeResponseEquals('[]');

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

$anotherReviewResult = factory(ReviewResult::class)->create([
    'review_id' => $review->id,
    'respondent_id' => $respondent->id,
    'interviewer_id' => $anotherInterviewer->id,
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

$I->sendGET(route('v1.review-results.get-interviewer-review-results', ['review_id' => $review->id]));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
    $reviewResult->toArray(),
]);

$I->cantSeeResponseContainsJson([
    $anotherReviewResult->toArray(),
]);
