<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class UserCode extends Model
{
    public $fillable = [
        'type',
        'code',
    ];
    
    CONST EMAIL_VERIFICATION = 'email_verification';
    
    CONST PASSWORD_RECOVERY = 'password_recovery';
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public static function generateCode()
    {
        return bin2hex(openssl_random_pseudo_bytes(6));
    }
}