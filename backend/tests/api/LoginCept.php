<?php

use Codeception\Util\HttpCode;
use App\User;
use Illuminate\Support\Facades\Hash;

$I = new ApiTester($scenario);

$email = 'test@email.ru';
$password = '123456Secure';
$user = factory(User::class)->create([
    'email' => $email,
    'password' => Hash::make($password),
    'email_verified_at' => now(),
]);

$I->sendPOST(route('auth.login'), [
    'email' => $email,
    'password' => 'wrongPassword',
]);
$I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
$I->seeResponseIsJson();

$I->sendPOST(route('auth.login'), [
    'email' => false,
    'password' => 1,
]);
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
$I->seeResponseIsJson();
$I->canSeeResponseMatchesJsonType([
    "email" => "Array",
    "password" => "Array",
]);


$I->sendPOST(route('auth.login'), [
    'email' => $email,
    'password' => $password,
]);
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseMatchesJsonType([
    'access_token' => 'string',
    'token_type' => 'string',
    'expires_in' => 'string',
]);
