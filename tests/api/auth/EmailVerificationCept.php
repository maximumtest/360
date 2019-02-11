<?php

use Codeception\Util\HttpCode;
use App\User;

$I = new ApiTester($scenario);

$user = factory(User::class)->create([
    'email' => $email,
    'password' => Hash::make($password),
    ]);

$verificationCode = factory()->create([
    'user_id' => $user->id,
]);

$I->sendPOST(route('email.verification', ['id' => 'wrongId']));
$I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
$I->seeResponseIsJson();

$I->sendPOST(route('email.verification', ['id' => $verificationCode]));
$I->seeResponseCodeIs(HttpCode::OK);
