<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthapiDeny
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
    	if (!Auth::once(['api_token' =>$request->input('api_token')])) {
    		return  oapi_response('Unauthorized');
    	}
        return $next($request);
    }
}
