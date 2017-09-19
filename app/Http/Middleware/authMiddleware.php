<?php namespace gesecole\Http\Middleware;

use Closure;
use gesecole\parametres;

class authMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{


		return $next($request);
	}

}
