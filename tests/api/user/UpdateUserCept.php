<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;

$I = new ApiTester($scenario);

// Проверяем, что не можем изменить пользователя без токена
$I->sendPATCH(route('v1.users.update', ['id' => 'notExistingId']));
$I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
$I->seeResponseIsJson();

// Создаем юзера с ролью employee и проверяем, что он не может изменить пользователя
$email = 'test12345@email.ru';
$password = '123456Secure';
$notAdmin = factory(User::class)->create([
    'email' => $email,
    'password' => Hash::make($password),
]);
$employeeRole = factory(Role::class)->create();
$notAdmin->assignRole($employeeRole);

$token = $I->getToken($email, $password);
$I->amBearerAuthenticated($token);

$newEmail = 'newEmail@email.ru';
$validParams = [
    'email' => $newEmail,
];
$I->sendPATCH(route('v1.users.update', ['id' => 'notExistingId']), $validParams);
$I->seeResponseCodeIs(HttpCode::FORBIDDEN);
$I->seeResponseIsJson();

// Создаем пользователя с ролью админ и отправляем не валидный запрос
$adminEmail = 'admin1@mail.ru';
$admin = factory(User::class)->create([
    'email' => $adminEmail,
    'password' => Hash::make($password),
]);
$adminRole = factory(Role::class)->create([
    'name' => Role::ROLE_ADMIN
]);
$admin->assignRole($adminRole);

$token = $I->getToken($adminEmail, $password);
$I->amBearerAuthenticated($token);

$invalidParams = [
    'email' => 123,
    'role_id' => false,
];
$I->sendPATCH(route('v1.users.update', ['id' => 'notExistingId']), $invalidParams);
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
$I->seeResponseIsJson();
$I->seeResponseMatchesJsonType([
    'errors' => [
        'email' => 'Array',
        'role_id' => 'Array',
    ],
]);

// Отправляем валидный запрос пользователем с ролью админ
$I->sendPATCH(route('v1.users.update', ['id' => $notAdmin->id]), $validParams);
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
    'email' => $newEmail,
]);
