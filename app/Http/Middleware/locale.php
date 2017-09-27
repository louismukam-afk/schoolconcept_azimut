<?php namespace gesecole\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class locale {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
	    if(session('locale')=='en'){
	        App::setLocale('en');
        }else{
	        App::setLocale('fr');
        }
		return $next($request);
	}

}
