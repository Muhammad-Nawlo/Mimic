<?php

namespace App\Http\Middleware;
use Closure;
use App;
use response;
class authGuest
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
        App::setlocale('en');

        if($request->header('lang') != null)
        {
            App::setlocale($request->header('lang'));
        }
        if($request->header('Apipassword') != 'mimic2022')
        {
            return response()->json([
                'status'=>'0',
                'message'=>"You Are Not Authorized",
            ]);
        }
        return $next($request);
    }
}
