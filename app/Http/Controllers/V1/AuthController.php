<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\ResetPasswordRequest;
use App\Http\Requests\V1\Auth\VerifyEmailRequest;
use App\UserCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use JWTAuth;

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
        $user = UserCode::where('code', $request->get('code'))
            ->first()
            ->user()
            ->first();

        if (!$user) {
            return response()->json(['message' => 'User or code not found'], 404);
        }

        $code = UserCode::where('code', $request->get('code'))->firstOrFail();

        $user->email_verified_at = now();
        $user->password = Hash::make($request->get('password'));
        $user->save();

        $code->delete();

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
        $user = UserCode::where('code', $request->get('code'))
            ->first()
            ->user()
            ->first();

        if (!$user) {
            return response()->json(['message' => 'User or code not found'], 404);
        }

        $user->password = Hash::make($request->get('password'));
        $user->save();

        return response()->json(['message' => 'Password successfully changed'], 200);
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
