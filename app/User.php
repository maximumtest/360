<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
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
        'role_ids',
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
            return $this->hasRole($roles);
        }

        return false;
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function assignRole(Role $role)
    {
        $this->roles()->attach($role->id);
    }

    public function attachUserToDepartment($departmentId)
    {
        $this->departments()->attach($departmentId);
    }

    public function getId()
    {
        return $this->id;
    }

    public function isAdmin()
    {
        return $this->hasRole(Role::ROLE_ADMIN);
    }

    public function isManager(Review $review)
    {
        return $this->getId() == $review->manager_id;
    }

    public function reviewResults()
    {
        return $this->hasMany(ReviewResult::class,'interviewer_id');
    }

    public function getAvatarAttribute($value)
    {
        return $value
            ? Storage::cloud()->url($value)
            : '';
    }

    public function getAvatarSrcAttribute()
    {
        return $this->attributes['avatar'] ?? '';
    }

    public function dropAvatar()
    {
        if ($this->avatarSrc) {
            $this->avatar = '';
            return Storage::cloud()->delete($this->avatarSrc);
        }

        return true;
    }
}
