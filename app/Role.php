<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Role extends Eloquent
{
    public $fillable = [
      'name',
    ];
    
    CONST ROLE_EMPLOYEE = 'employee';
    CONST ROLE_MANAGER = 'manager';
    CONST ROLE_ADMIN = 'admin';
    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}