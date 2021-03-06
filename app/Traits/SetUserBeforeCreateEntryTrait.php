<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait SetUserBeforeCreateEntryTrait
{
    public static function bootSetUserBeforeCreateEntryTrait()
    {
        static::creating(function ($model) {
            $userIdField = $model->userIdField ?: 'author_id';

            if (Auth::user()) {
                $model->{$userIdField} = $model->{$userIdField} ?? Auth::id();
            }
        });
    }
}
