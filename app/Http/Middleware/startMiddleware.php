<?php namespace gesecole\Http\Middleware;

use Closure;
use gesecole\parametres;

class startMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
	    $p=parametres::where('nom','=','configuration')->first();
	    if($p!=null){
	        return redirect()->to('/');
        }
		return $next($request);
	}

}
