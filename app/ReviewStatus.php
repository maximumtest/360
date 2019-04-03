<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class ReviewStatus extends Model
{
    const STATUS_DRAFT = 'draft';
    const STATUS_IN_PROGRESS = 'in progress';
    const STATUS_PAUSED = 'paused';
    const STATUS_FINISHED = 'finished';

    protected $fillable = [
        'name',
    ];
}
