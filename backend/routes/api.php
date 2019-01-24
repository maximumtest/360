<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/auth',
], function (){
    Route::post('login', 'Auth\LoginController@login')
        ->name('auth.login');
    Route::post('email/verification/{id}', 'Auth\RegisterController@emailVerification')
        ->name('email.verification');
    Route::get('logout', 'Auth\LoginController@logout')
        ->name('auth.logout');
});