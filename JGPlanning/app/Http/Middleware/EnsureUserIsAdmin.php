<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Exception
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if($user['role_id'] == Role::getRoleID('employee')){
            abort(403);
        }

        return $next($request);
    }
}
