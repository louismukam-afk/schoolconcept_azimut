<?php namespace gesecole\Http\Middleware\privileges;

use Closure;

class g_personnel {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        if(Auth::user()->role>2 && Auth::user()->role!=17){
            return view('not_auth',['titre'=>'']);
        }
		return $next($request);
	}

}
