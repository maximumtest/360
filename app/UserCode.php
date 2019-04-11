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
        $userCode = UserCode::firstOrNew([
            'type' => self::EMAIL_VERIFICATION,
            'user_id' => $user->_id,
        ]);

        if (!$userCode->exists) {
            $userCode->code = self::generateCode();
            $userCode->save();

            $user->codes()->save($userCode);
        }

        return $userCode;
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
