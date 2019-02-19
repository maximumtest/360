<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param $role
     * @throws \Exception
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (Auth::guest()) {
            throw new \Exception('Unauthorized', 401);
        }
    
        $roles = is_array($role)
            ? $role
            : explode('|', $role);
    
        if (!Auth::user()->hasAnyRole($roles)) {
            throw new \Exception('Forbidden', 403);
        }
        
        return $next($request);
    }
}
