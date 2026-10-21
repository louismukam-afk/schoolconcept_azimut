<?php namespace gesecole\Http\Middleware\privileges;

use Closure;
use gesecole\Functions;
use Illuminate\Support\Facades\Auth;

class g_editeur {

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

        if(!Functions::pp_exists($r,2)){
            return view('not_auth',['titre'=>'']);
        }*/
		return $next($request);
	}

}
