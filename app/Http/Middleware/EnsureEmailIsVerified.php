<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;

class EnsureEmailIsVerified
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
        if (! $request->user() ||
             ($request->user() instanceof MustVerifyEmail &&
             ! $request->user()->hasVerifiedEmail())) {

            if($request->expectsJson()){
                return response()->json([
                    'success' => false,
                    'status' => false,
                    'message' => 'Your email address is not verified.'
                ],422);
            }
         }
            
        return $next($request);
    }
}
