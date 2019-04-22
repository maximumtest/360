<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\ResetPasswordRequest;
use App\Http\Requests\V1\Auth\VerifyEmailRequest;
use App\User;
use App\UserCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;
use App\Http\Requests\V1\Auth\ResetLinkRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];

        $token = JWTAuth::attempt($credentials);

        if (!$token) {
            return response()->json(['message' => 'Wrong credentials'], 401);
        }

        return response()->json($this->getResponseWithToken($token), 200);
    }

    public function verifyEmail(VerifyEmailRequest $request): JsonResponse
    {
        $userId = UserCode::redeem($request->get('code'));

        $user = User::findOrFail($userId);
        $user->update([
            'email_verified_at' => now(),
            'password' => Hash::make($request->get('password')),
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => $request->get('password'),
        ];

        $token = JWTAuth::attempt($credentials);

        return response()->json($this->getResponseWithToken($token), 200);
    }

    public function logout(): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function me(): JsonResponse
    {
        return response()->json(JWTAuth::parseToken()->authenticate()->only(['_id', 'name', 'email']), 200);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $userId = UserCode::redeem($request->get('code'), UserCode::PASSWORD_RECOVERY);

        $user = User::findOrFail($userId);
        $user->update([
            'password' => Hash::make($request->get('password')),
        ]);

        return response()->json(['message' => 'Password successfully changed'], 200);
    }
    
    public function generateResetLink(ResetLinkRequest $request)
    {
        $user = User::where('email', $request->get('email'))->firstOrFail();
        $userCode = UserCode::create($user, UserCode::PASSWORD_RECOVERY);
    
        Mail::to($user->email)->send(new PasswordReset($userCode));
    
        return response()->json(['data' => 'Mail sent'], 200);
    }

    protected function getResponseWithToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL(),
        ];
    }
}
