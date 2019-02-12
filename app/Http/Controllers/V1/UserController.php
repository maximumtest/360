<?php

namespace App\Http\Controllers\V1;

use App\User;
use App\UserCode;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\V1\User\UpdateUserRequest;
use App\Http\Requests\V1\User\CreateUserRequest;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::all();
    
        return response()->json($users);
    }
    
    public function store(CreateUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
    
        $code = new UserCode();
        $code->type = UserCode::EMAIL_VERIFICATION;
        $code->generateCode();
        $user->codes()->save($code);
        
        return response()->json($user, 201);
    }
    
    public function show(string $id): JsonResponse
    {
        $user = User::find($id);
        
        return response()->json($user, 200);
    }
    
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        $user = User::find($id);
        
        $user->fill($request->validated());
        $user->save();
        
        return response()->json($user, 200);
    }
    
    public function destroy(string $id): JsonResponse
    {
        User::find($id)->delete();
        
        return response()->json(null, 204);
    }
}
