<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if($request->user()->role != 'admin'){
            return response([
                'status' => 401,
                'message' => 'You dont have access',
            ]);
        }
        return $next($request);
    }
}
