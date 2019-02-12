<?php

use Codeception\Util\HttpCode;
use App\User;

$I = new ApiTester($scenario);

$email = 'test@email.ru';
$password = '123456Secure';
$user = factory(User::class)->create([
    'email' => $email,
    'password' => Hash::make($password),
]);

$token = $I->getToken($email, $password);
$I->amBearerAuthenticated($token);

$I->sendGET(route('auth.logout'));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseIsJson();

$token = 'wrongToken';
$I->amBearerAuthenticated($token);
$I->sendGET(route('auth.logout'));
$I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
$I->seeResponseIsJson();
