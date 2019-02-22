<?php

use Tests\ApiTester;
use \Codeception\Util\HttpCode;
use Faker\Factory;
use App\User;
use App\Template;
use Illuminate\Support\Facades\Hash;
use App\Role;

$I = new ApiTester($scenario);
$faker = Factory::create();

// Проверяем, что не можем создать ревью без токена
$I->sendPOST(route('v1.reviews.store', [
    'template_id' => 'someId',
    'title' => 'someTitle',
]));
$I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
$I->seeResponseIsJson();

// Создаем юзера с ролью employee и проверяем, что он не может создать ревью
$email = 'test12345@email.ru';
$password = '123456Secure';
$notAdmin = factory(User::class)->create([
    'email' => $email,
    'password' => Hash::make($password),
]);
$employeeRole = factory(Role::class)->create();
$notAdmin->assignRole($employeeRole->id);

$token = $I->getToken($email, $password);
$I->amBearerAuthenticated($token);

$templateId = factory(Template::class)->create()->id;
$title = $faker->text(20);

$I->sendPOST(route('v1.reviews.store', [
    'template_id' => $templateId,
    'title' => $title,
]));
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
$admin->assignRole($adminRole->id);

$token = $I->getToken($adminEmail, $password);
$I->amBearerAuthenticated($token);
$I->sendPOST(route('v1.reviews.store', [
    'template_id' => $templateId,
    'title' => $title,
]));

$I->seeResponseCodeIs(HttpCode::CREATED);
$I->seeResponseIsJson();
$I->canSeeResponseContainsJson([
    'template_id' => $templateId,
    'title' => $title,
]);
