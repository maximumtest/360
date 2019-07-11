<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;

$I = new ApiTester($scenario);

// Проверяем, что не можем получить пользователя без токена
$I->sendGET(route('v1.users.show', ['id' => 'notExistingId']));
$I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
$I->seeResponseIsJson();

// Создаем юзера с ролью employee и проверяем, что он не может получить пользователя
$email = 'testtest@email.ru';
$password = '123456Secure';
$notAdmin = factory(User::class)->create([
    'email' => $email,
    'password' => Hash::make($password),
]);
$employeeRole = factory(Role::class)->create();
$notAdmin->assignRole($employeeRole);

$token = $I->getToken($email, $password);
$I->amBearerAuthenticated($token);
$I->sendGET(route('v1.users.show', ['id' => 'notExistingId']));
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

$I->sendGET(route('v1.users.show', ['id' => 'notExistingId']));
$I->seeResponseCodeIs(HttpCode::NOT_FOUND);
$I->seeResponseIsJson();

$I->sendGET(route('v1.users.show', ['id' => $notAdmin->id]));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseIsJson();
$I->seeResponseContainsJson([
    'email' => $notAdmin->email,
    'updated_at' => $notAdmin->updated_at->format('Y-m-d H:i:s'),
    'created_at' => $notAdmin->updated_at->format('Y-m-d H:i:s'),
    '_id' => $notAdmin->id,
    'role_id' => $notAdmin->role_id
]);

$I->sendGET(route('v1.users.index'));
$I->seeResponseCodeIs(HttpCode::OK);
$I->seeResponseIsJson();
