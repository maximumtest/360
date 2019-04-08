<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use App\Traits\SetUserBeforeCreateEntryTrait;

class KudosTag extends Model
{
    use SetUserBeforeCreateEntryTrait;

    protected $fillable = [
        'author_id',
        'name',
    ];
}
