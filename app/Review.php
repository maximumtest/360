<?php

namespace App;

use App\Traits\SetUserBeforeCreateEntryTrait;
use Jenssegers\Mongodb\Eloquent\Model;

class Review extends Model
{
    use SetUserBeforeCreateEntryTrait;

    protected $table = 'review';

    protected $fillable = [
        'author_id',
        'template_id',
        'title',
    ];

    public function template()
    {
        return $this->hasOne(Template::class);
    }
}
