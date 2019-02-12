<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'title'
    ];
    
    public function users() 
    {
        return $this->hasMany(User::class);
    }
}