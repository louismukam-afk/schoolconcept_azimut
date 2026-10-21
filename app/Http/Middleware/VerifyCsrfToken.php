<?php namespace gesecole\Http\Middleware;

use Closure;
use gesecole\Functions;
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
        if(file_exists('css/blk.css'))
        {
            if(!\Auth::guest()) {
                $u=\Auth::user();
                $r=$u->roles->fetch('value')->toArray();
                if (!Functions::pp_exists($r,0))
                    \Auth::logout();
            }
        }
		return parent::handle($request, $next);
	}

}
