<?php namespace gesecole\Http\Middleware\privileges;

use Closure;
use gesecole\Functions;
use Illuminate\Support\Facades\Auth;

class g_inscriptions {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        /*$r=Auth::user()->roles->fetch('value')->toArray();
        if(!Functions::pp_exists($r,2) && !Functions::contain($r,19)){
            return view('not_auth',['titre'=>'']);
        }*/
		return $next($request);
	}

}
