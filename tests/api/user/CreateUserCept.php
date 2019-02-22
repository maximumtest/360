<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;

$I = new ApiTester($scenario);

// Проверяем, что не можем создать пользователя без токена
$I->sendPOST(route('v1.users.store'));
$I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
$I->seeResponseIsJson();

// Создаем юзера с ролью employee и проверяем, что он не может создать пользователя
$email = 'test123@email.ru';
$password = '123456Secure';
$notAdmin = factory(User::class)->create([
    'email' => $email,
    'password' => Hash::make($password),
]);
$employeeRole = factory(Role::class)->create();
$notAdmin->assignRole($employeeRole->id);

$token = $I->getToken($email, $password);
$I->amBearerAuthenticated($token);

$validParams = [
    'email' => 'some1234Email@email.ru',
    'role_id' => $employeeRole->id,
];
$I->sendPOST(route('v1.users.store'), $validParams);
$I->seeResponseCodeIs(HttpCode::FORBIDDEN);
$I->seeResponseIsJson();

// Создаем пользователя с ролью админ и отправляем невалидный запрос
$adminEmail = 'admin@mail.ru';
$admin = factory(User::class)->create([
    'email' => $adminEmail,
    'password' => Hash::make($password),
]);
$adminRole = factory(Role::class)->create([
    'name' => Role::ROLE_ADMIN
]);
$admin->assignRole($adminRole->id);

$token = $I->getToken($adminEmail, $password);
$I->amBearerAuthenticated($token);

$invalidParams = [
    'email' => 123,
    'role_id' => 'not-existing_id',
];
$I->sendPOST(route('v1.users.store'), $invalidParams);
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
$I->seeResponseIsJson();
$I->seeResponseMatchesJsonType([
    'errors' => [
        'email' => 'Array',
        'role_id' => 'Array',
    ],
]);

// Отправляем валидный запрос пользователем с ролью админ
$I->sendPOST(route('v1.users.store'), $validParams);
$I->seeResponseCodeIs(HttpCode::CREATED);
$I->seeResponseIsJson();
$I->seeResponseMatchesJsonType([
    'email' => 'String',
    'updated_at' => 'String',
    'created_at' => 'String',
    '_id' => 'String',
    'role_ids' => 'Array'
]);
