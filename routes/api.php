<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'namespace' => 'V1',
    'as' => 'v1.',
], function () {
    Route::group([
        'middleware' => 'role:admin',
    ], function () {
        Route::apiResources([
            'users' => 'UserController',
        ]);
    });

    Route::group([
//        'middleware' => 'role:admin',
    ], function () {
        Route::get('questions/filter', 'QuestionController@filter')->name('questions.filter');

        Route::apiResources([
            'reviews' => 'ReviewController',
            'templates' => 'TemplateController',
            'questions' => 'QuestionController',
        ]);
    });

    Route::get('review-statuses', 'ReviewStatusController@getAll')->name('review-statuses.index');
    Route::get('question-types', 'QuestionTypeController@getAll')->name('question-types.index');
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
        Route::get('logout', 'AuthController@logout')
            ->name('auth.logout');
        Route::get('me', 'AuthController@me')
            ->name('auth.me');
    });
});
