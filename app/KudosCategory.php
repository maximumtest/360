<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class KudosCategory extends Model
{
    protected $fillable = [
        'name',
    ];

    public function kudos()
    {
        return $this->hasMany(Kudos::class, 'kudos_category_id');
    }
}
