<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;


class JWTAuthentication
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
        try{
            $user = JWTAuth::parseToken()->authenticate();

        }catch(\Exception $e){
            if($e instanceof TokenExpiredException){
                
                $newToken = JWTAuth::parseToken()->refresh();

                // $newToken = $newToken->authenticate();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Token Expired',
                    'new_token' => $newToken
                ],200);

            }else if($e instanceof TokenInvalidException){
                return response()->json([
                    'success' => false,
                    'message' => 'Token Invalid'
                ],401);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Token Not Found'
                ],401);
            }
        }

        return $next($request);
    }
}
