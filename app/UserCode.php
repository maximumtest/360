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
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public static function create(User $user, string $type)
    {
        $userCode = UserCode::firstOrNew([
            'type' => $type,
            'user_id' => $user->_id,
        ]);

        $userCode->code = self::generateCode();

        $user->codes()->save($userCode);

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
