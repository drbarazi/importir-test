<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }
        return response()->json(['status' => 'fail', 
                                'message' => "You Don't Have Permission to Access",
                                'data' => null
                                ], 403);
    }
}
