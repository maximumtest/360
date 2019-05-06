<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'namespace' => 'V1',
    'as' => 'v1.',
], function () {
    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('login', 'AuthController@login')
            ->name('auth.login');
        Route::post('email/verification', 'AuthController@verifyEmail')
            ->name('auth.email.verification');
        Route::post('password/link', 'AuthController@generateResetLink')
            ->name('auth.password.link');
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

    Route::group([
        'middleware' => 'jwt.auth',
    ], function () {
        Route::get('questions/filter', 'QuestionController@filter')->name('questions.filter');
        Route::get('kudos-tags/filter', 'KudosTagController@filter')->name('kudos-tags.filter');
        Route::get('users/filter', 'UserController@filter')->name('users.filter');

        Route::group([
            'middleware' => 'role:admin',
        ], function () {
            Route::apiResources([
                'users' => 'UserController',
            ]);
        });

        Route::apiResources([
            'reviews' => 'ReviewController',
            'templates' => 'TemplateController',
            'questions' => 'QuestionController',
            'review-results' => 'ReviewResultController',
            'kudos-categories' => 'KudosCategoryController',
            'kudos-tags' => 'KudosTagController',
            'users/{user_to}/kudos' => 'KudosController',
        ]);

        Route::get('review-statuses', 'ReviewStatusController@getAll')->name('review-statuses.index');
        Route::get('question-types', 'QuestionTypeController@getAll')->name('question-types.index');
    });
});
