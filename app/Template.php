<?php

namespace App;

use App\Traits\SetUserBeforeCreateEntryTrait;
use Jenssegers\Mongodb\Eloquent\Model;

class Template extends Model
{
    use SetUserBeforeCreateEntryTrait;

    protected $table = 'template';

    protected $fillable = [
        'title',
        'author_id',
    ];

    public function reviews()
    {
        return $this->belongsToMany(Review::class);
    }
}
