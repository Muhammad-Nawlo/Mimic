<?php

namespace App\Http\Middleware;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use response;
class authClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            config(['auth.defaults.guard' => 'client']);
            $user = JWTAuth::parseToken()->authenticate();
            $token = JWTAuth::getToken();
            $payload = JWTAuth::getPayload($token)->toArray();
            if ($payload['type'] != 'client') {
                return response()->json([
                    'status'=>false,
                    'message'=>'Not authorized',
                ],400);
            }
            if($user->status=='Blocked')
            {
                return response()->json([
                    'status'  => false,
                    'message' => __('site.You Account is Blocked'),
                ], 200);
            }
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException ) {
                return response()->json([
                    'status'=>false,
                    'message'=>'Token is Invalid',
                ],400);
            } else if ($e instanceof TokenExpiredException) {
                return response()->json([
                    'status'=>false,
                    'message'=>'Token is Expired',
                ],400);
            }
            else if ($e instanceof  TokenBlacklistedException) {
                return response()->json([
                    'status'=>false,
                    'message'=>'Token is blacklist',
                ],400);
            }
            else {
                return response()->json([
                    'status'=>false,
                    'message'=>'Authorization Token not found',
                ],400);
            }
        }

        return $next($request);
    }
}
