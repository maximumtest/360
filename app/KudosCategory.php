<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class KudosCategory extends Model
{
    protected $fillable = [
        'name',
    ];
}
