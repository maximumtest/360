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
        do {
            $code = bin2hex(openssl_random_pseudo_bytes(6));
        } while (static::where('code', $code)->count() > 0);

        return $code;
    }

    public static function generateEmailVerificationCode(User $user)
    {
        $code = new UserCode();
        $code->type = self::EMAIL_VERIFICATION;
        $code->code = self::generateCode();
        $code->save();
        $user->codes()->save($code);

        return $code;
    }

    public static function redeem(string $code, string $type = self::EMAIL_VERIFICATION): string
    {
        $userCode = static::where('code', $code)
            ->where('type', $type)
            ->firstOrFail();

        $ownerUserId = $userCode->user_id;

        $userCode->delete();

        return $ownerUserId;
    }
}
