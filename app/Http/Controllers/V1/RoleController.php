<?php

namespace App\Http\Controllers\V1;

use App\Role;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Role::all();

        return response()->json($roles, 200);
    }
}
