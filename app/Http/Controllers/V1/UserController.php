<?php

namespace App\Http\Controllers\V1;

use App\Mail\Registration;
use App\User;
use App\UserCode;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\V1\User\UpdateUserRequest;
use App\Http\Requests\V1\User\CreateUserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

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
        $user->assignRole($request->get('role_id'));

        $userCode = UserCode::create($user, UserCode::EMAIL_VERIFICATION);

        if ($request->has('department_id')) {
            $user->attachUserToDepartment($request->get('department_id'));
        }

        Mail::to($user->email)->send(new Registration($userCode));

        return response()->json($user, 201);
    }

    public function show(string $id): JsonResponse
    {
        $user = User::findOrFail($id);

        return response()->json($user, 200);
    }

    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->fill($request->validated());
        $user->save();

        if ($request->has('role_id')) {
            $user->assignRole($request->get('role_id'));
        }

        if ($request->has('department')) {
            $user->attachUserToDepartment($request->get('department_id'));
        }

        return response()->json($user, 200);
    }

    public function destroy(string $id): JsonResponse
    {
        User::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
