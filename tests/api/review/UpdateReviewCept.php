<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use Faker\Factory;
use App\User;
use App\Review;
use App\Template;
use App\ReviewStatus;

$I = new ApiTester($scenario);
$faker = Factory::create();

$user = factory(User::class)->create();
$token = $I->getToken($user->email, 123);
$I->amBearerAuthenticated($token);

$I->sendPATCH(route('v1.reviews.update', ['review' => $faker->word]));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);

$review = factory(Review::class)->create();

$newTemplateId = factory(Template::class)->create()->id;
$newTitle = $faker->text(15);
$newReviewStatusId = factory(ReviewStatus::class)->create()->id;
$newUserIds = factory(User::class, 3)->create()->pluck('id');

$I->sendPATCH(route('v1.reviews.update', ['review' => $review->_id]), [
    'template_id' => $newTemplateId,
    'title' => $newTitle,
    'review_status_id' => $newReviewStatusId,
    'users' => [$newUserIds[0], $newUserIds[0]],
]);
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

$I->sendPATCH(route('v1.reviews.update', ['review' => $review->_id]), [
    'template_id' => $newTemplateId,
    'title' => $newTitle,
    'review_status_id' => $newReviewStatusId,
    'users' => $newUserIds,
]);
$I->seeResponseCodeIs(HttpCode::OK);
