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
    
    protected $hidden = [
        'remember_token',
        'password',
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
        return $this->belongsToMany(Role::class);
    }
    
    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        
        return false;
    }
    
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }
    
    public function assignRole($roleId)
    {
        $this->roles()->attach($roleId);
    }
    
    public function attachUserToDepartment($departmentId)
    {
        $this->departments()->attach($departmentId);
    }
    
    public function getId()
    {
        return $this->id;
    }
}
