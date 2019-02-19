<?php

namespace App\Http\Controllers\V1;

use App\Department;
use App\Role;
use App\User;
use App\UserCode;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\V1\User\UpdateUserRequest;
use App\Http\Requests\V1\User\CreateUserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::all();
    
        return response()->json($users);
    }
    
    public function store(CreateUserRequest $request): JsonResponse
    {
        $params = array_merge(
            $request->validated(),
            [
                'password' => Hash::make($request->get('password')),
            ]
        );
        $user = User::create($params);
        
        $code = new UserCode();
        $code->type = UserCode::EMAIL_VERIFICATION;
        $code->code = UserCode::generateCode();
        $user->codes()->save($code);
        
        $role = Role::where('name', $request->get('role'))->first();
        $user->roles()->attach($role->id);
        
        if ($request->has('department')) {
            $department = Department::where('name', $request->get('department'))->first();
            $user->departments()->attach($department->id);
        }
        
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
        
        if ($request->has('role')) {
            $role = Role::where('name', $request->get('role'))->first();
            $user->roles()->attach($role->id);
        }
        
        if ($request->has('department')) {
            $department = Department::where('name', $request->get('department'))->first();
            $user->departments()->attach($department->id);
        }
        
        return response()->json($user, 200);
    }
    
    public function destroy(string $id): JsonResponse
    {
        User::find($id)->delete();
        
        return response()->json(null, 204);
    }
}
