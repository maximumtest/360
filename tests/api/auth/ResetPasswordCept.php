<?php

use Codeception\Util\HttpCode;
use App\User;
use App\UserCode;
use Tests\ApiTester;
use Illuminate\Support\Facades\Hash;

$I = new ApiTester($scenario);

$email = 'some@email.ru';
$oldPassword = 'verySecure123';
$user = factory(User::class)->create([
    'email' => $email,
    'password' => Hash::make($oldPassword),
    'email_verified_at' => now(),
]);
$userCode = factory(UserCode::class)->create([
    'type' => UserCode::PASSWORD_RECOVERY,
]);
$user->codes()->save($userCode);

$invalidParamsFirst = [
    'password' => '123',
    'code' => 123,
    'email' => null,
];

$I->sendPOST(route('v1.auth.password.reset'), $invalidParamsFirst);
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
$I->seeResponseMatchesJsonType([
    'errors' => [
        'email' => 'Array',
        'password' => 'Array',
        'code' => 'Array',
    ],
]);

$newPassword = 'newPassword123';
$invalidParamsSecond = [
    'password' => $newPassword,
    'code' => '123',
    'email' => $email,
];
$I->sendPOST(route('v1.auth.password.reset'), $invalidParamsSecond);
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);

$validParams = [
    'password' => 'newPassword123',
    'code' => $userCode->code,
    'email' => $email,
];
$I->sendPOST(route('v1.auth.password.reset'), $validParams);
$I->seeResponseCodeIs(HttpCode::OK);

$validLogin = [
    'email' => $email,
    'password' => $newPassword,
];
$I->sendPOST(route('v1.auth.login'), $validLogin);
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseMatchesJsonType([
    'access_token' => 'String',
    'token_type' => 'String',
    'expires_in' => 'Integer',
]);

$invalidLogin = [
    'email' => $email,
    'password' => $oldPassword,
];
$I->sendPOST(route('v1.auth.login'), $invalidLogin);
$I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
