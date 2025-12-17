<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsCreatorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {   
        $user = auth()->user();
        if($user->role == $roles){
            return $next($request);
        }else{
            abort(403, 'Unauthorized');
        }
        
        // if (!in_array(auth()->user()->role, [2, 3])) {
        //     abort(403, 'Unauthorized');
        // }
        // return $next($request);
    }
}
