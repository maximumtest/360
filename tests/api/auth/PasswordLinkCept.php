<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use Illuminate\Support\Facades\Mail;

Mail::fake();

$I = new ApiTester($scenario);

$I->sendPOST(route('v1.auth.password.link', ['email' => 'notExisting@email.ru']));
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
$I->seeResponseIsJson();
$I->seeResponseMatchesJsonType([
    'message' => 'String',
    'errors' => [
        'email' => 'Array',
    ],
]);

$user = factory(User::class)->create();

$I->sendPOST(route('v1.auth.password.link'), ['email' => $user->email]);
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
    'data' => 'Mail sent',
]);