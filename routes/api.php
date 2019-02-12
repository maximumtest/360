<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'namespace' => 'V1',
    'as' => 'v1.',
], function () {
    Route::apiResources([
        'reviews' => 'ReviewController',
        'templates' => 'TemplateController',
        'users' => 'UserController',
    ]);
});

Route::group([
    'prefix' => 'v1/auth',
    'namespace' => 'V1',
    'as' => 'v1.',
], function (){
    Route::post('login', 'AuthController@login')
        ->name('auth.login');
    Route::post('email/verification', 'AuthController@verifyEmail')
        ->name('auth.email.verification');
    Route::post('password/reset', 'AuthController@resetPassword')
        ->name('auth.password.reset');
    
    Route::group([
        'middleware' => 'jwt.auth',
    ], function () {
        Route::get('logout', 'LoginController@logout')
            ->name('auth.logout');
    });
});