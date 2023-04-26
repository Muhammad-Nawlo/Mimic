<?php

namespace App\Http\Middleware;

use App\Interfaces\MustVerifyMobile;
use Closure;

class EnsureMobileIsVerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth('client')->user() ||
            (auth('client')->user() instanceof MustVerifyMobile &&
                !auth('client')->user()->hasVerifiedMobile())) {
            return response()->json([
                'status' => false,
                'message' => __('site.Mobile Number not Verified'),
            ], 403);
        }
        return $next($request);
    }
}
