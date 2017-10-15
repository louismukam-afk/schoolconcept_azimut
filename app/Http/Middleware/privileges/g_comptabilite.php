<?php namespace gesecole\Http\Middleware\privileges;

use Closure;
use Illuminate\Support\Facades\Auth;

class g_comptabilite {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        if(Auth::user()->role>1 && Auth::user()->role!=16){
            return view('not_auth',['titre'=>'']);
        }
		return $next($request);
	}

}
