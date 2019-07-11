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
        return $this->hasMany(User::class);
    }
    
    public static function findByName(string $name)
    {
        return Role::where('name', $name)->firstOrFail();
    }
}