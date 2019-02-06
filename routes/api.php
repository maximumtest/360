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
    ]);
});
