<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            //User are not authorize
            abort(401, 'You need authorize to use this functions');
        }
        if (empty($roles)) {
            return $next($request);
        }
        //Defining the user's role
        $userRole = Auth::user()->role;
        //Check that user's role in arrays
        if (!in_array($userRole, $roles)) {
            abort(403, "You don't have access permissions");
        }
        return $next($request);
    }
}
