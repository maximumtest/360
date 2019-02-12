<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $collection = 'users';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
    ];
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    public function codes()
    {
        return $this->hasMany(UserCode::class);
    }
    
    public function roles()
    {
        return $this->hasMany(Role::class);
    }
}
