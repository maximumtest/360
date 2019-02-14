<?php

use Codeception\Util\HttpCode;
use App\User;
use Tests\ApiTester;
use App\UserCode;

$I = new ApiTester($scenario);

$email = 'test@test.ru';
$password = '123pass';
$user = factory(User::class)->create([
    'email' => $email,
    'password' => Hash::make($password),
]);
$userCode = factory(UserCode::class)->create([
    'user_id' => $user->id,
    'type' => UserCode::EMAIL_VERIFICATION,
]);

$invalidParamsFirst = [
    'email' => 'not@existing.com',
    'password' => '123',
    'code' => 123,
];

$I->sendPOST(route('auth.email.verification', $invalidParamsFirst));
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
$I->seeResponseMatchesJsonType([
    'email' => 'Array',
    'password' => 'Array',
    'code' => 'Array',
]);

$invalidParamsSecond = [
    'email' => $user->email,
    'password' => '123456',
    'code' => '123',
];

$I->sendPOST('auth.email.verification', $invalidParamsSecond);
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);
$I->seeResponseIsJson();

$validParams = [
    'email' => $user->email,
    'password' => '123456',
    'code' => $userCode->code,
];
$I->sendPOST(route('auth.email.verification', $validParams));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseMatchesJsonType([
    'access_token' => 'String',
    'token_type' => 'String',
    'expires_in' => 'String',
]);
