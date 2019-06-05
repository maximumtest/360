<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\V1\User\UpdateProfileRequest;
use App\Mail\Registration;
use App\Role;
use App\User;
use App\UserCode;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\V1\User\UpdateUserRequest;
use App\Http\Requests\V1\User\CreateUserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;

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
        $user->assignRole(Role::findOrFail($request->get('role_id')));

        $userCode = UserCode::generate($user, UserCode::EMAIL_VERIFICATION);

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
            $user->assignRole(Role::findOrFail($request->get('role_id')));
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

    public function filter(Request $request): JsonResponse
    {
        $searchTerm = $request->input('searchTerm');

        $users = User::where('name', 'like', "%{$searchTerm}%")->orWhere('email', 'like', "%{$searchTerm}%")->get();

        if ($users->count() === 0) {
            throw new NotFoundHttpException();
        }

        return response()->json($users, 200);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = Auth::user();

        try {
            if ($request->hasFile('avatar')) {
                $avatar = Storage::cloud()->putFile('users/avatars', $request->file('avatar'));

                $user->dropAvatar();

                $user->avatar = $avatar;
            }

            if ($request->input('removeAvatar', false)) {
                $user->dropAvatar();
            }

            if ($request->exists('password') && $request->input('password')) {
                $user->password = Hash::make($request->input('password'));
            }
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }

        $user->save();

        return response()->json([
            'avatar' => $user->avatar,
        ], 200);
    }
}
