<?php

use Codeception\Util\HttpCode;
use App\User;
use Tests\ApiTester;
use Illuminate\Support\Facades\Hash;

$I = new ApiTester($scenario);

$email = 'MeMe@email.ru';
$password = '123456Secure';
$user = factory(User::class)->create([
    'email' => $email,
    'password' => Hash::make($password),
]);

$token = $I->getToken($email, $password);
$I->amBearerAuthenticated($token);
$I->sendGET(route('v1.auth.me'));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
    '_id' => $user->id,
    'name' => $user->name,
    'email' => $user->email,
]);

$token = 'wrongToken';
$I->amBearerAuthenticated($token);
$I->sendGET(route('v1.auth.me'));
$I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
$I->seeResponseIsJson();
