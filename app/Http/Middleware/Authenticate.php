<?php namespace gesecole\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use gesecole\parametres;

class Authenticate {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		if ($this->auth->guest())
		{

            $p=parametres::all();
            $p->where('nom','configuration',false)->first();
            if($p==null){
                return redirect()->to('/start');
            }
			if ($request->ajax())
			{
				return response('Unauthorized.', 401);
			}
			else
			{
				return redirect()->guest('auth/login');
			}
		}
		if (\Auth::user()->active==0){
		    session(['account_deactivate'=>trans('admin.activer_compte')]);
		    Auth::logout();
           return redirect()->guest('auth/login');
        }

		return $next($request);
	}

}
