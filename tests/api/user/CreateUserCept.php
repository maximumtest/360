<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

$I = new ApiTester($scenario);

Mail::fake();

$password = '123456Secure';
$adminEmail = 'admin@mail.ru';
$email = 'test123@email.ru';
$employeeRole = factory(Role::class)->create();
$adminRole = factory(Role::class)->create([
    'name' => Role::ROLE_ADMIN
]);
$validParams = [
    'email' => 'some1234Email@email.ru',
    'role_id' => $employeeRole->id,
];
$invalidParams = [
    'email' => 123,
    'role_id' => 'not-existing_id',
];

// Создаем пользователя с ролью админ
$admin = factory(User::class)->create([
    'email' => $adminEmail,
    'password' => Hash::make($password),
]);
$admin->assignRole($adminRole->id);

$token = $I->getToken($adminEmail, $password);
$I->amBearerAuthenticated($token);

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

// Отправляем невалидный запрос пользователем с ролью админ
$I->sendPOST(route('v1.users.store'), $invalidParams);
$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
$I->seeResponseIsJson();
$I->seeResponseMatchesJsonType([
    'errors' => [
        'email' => 'Array',
        'role_id' => 'Array',
    ],
]);

// Проверяем, что не можем создать пользователя без токена
$I->amBearerAuthenticated('');
$I->sendPOST(route('v1.users.store'));
$I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
$I->seeResponseIsJson();

// Создаем юзера с ролью employee и проверяем, что он не может создать пользователя
$notAdmin = factory(User::class)->create([
    'email' => $email,
    'password' => Hash::make($password),
]);
$notAdmin->assignRole($employeeRole->id);

$token = $I->getToken($email, $password);
$I->amBearerAuthenticated($token);

$I->sendPOST(route('v1.users.store'), $validParams);
$I->seeResponseCodeIs(HttpCode::FORBIDDEN);
$I->seeResponseIsJson();
