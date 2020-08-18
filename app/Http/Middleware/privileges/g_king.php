<?php namespace gesecole\Http\Middleware\privileges;

use Closure;
use gesecole\Functions;
use Illuminate\Support\Facades\Auth;
class g_king {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        if(!Functions::pp_exists(Auth::user()->roles->fetch('value')->toArray(),0)){
            return view('not_auth',['titre'=>'']);
        }
		return $next($request);
	}

}
