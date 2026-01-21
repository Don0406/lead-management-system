<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Ensure the user is even logged in
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        // 2. Check if the user's role matches any of the allowed roles passed from the route
        foreach ($roles as $role) {
            if ($user->role === $role) {
                return $next($request);
            }
        }

        // 3. If no match is found, block access
        abort(403, 'Unauthorized action.');
    }
}