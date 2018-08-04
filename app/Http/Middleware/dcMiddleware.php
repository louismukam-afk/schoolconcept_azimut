<?php namespace gesecole\Http\Middleware;

use Closure;
use gesecole\parametres;

class dcMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
	    if(session('dc_interval')!=null) {
            $d = session('dc_interval');
            $f = microtime(true);
            $i = $f - $d;
            if($i<0)
                return view('w_date');
            if (($i)>=600)
            {
                $dc=parametres::whereNom('dc')->first();
                if ($dc==null)
                {
                    $dc=new parametres();
                    $dc->nom='dc';
                    $dc->valeur=date('Y-m-d');
                    $dc->save();
                    session(['dc_interval'=>microtime(true)]);
                }else{
                    $d=date('Y-m-d');
                    if($d>=$dc->valeur)
                    {
                        $dc->valeur=date('Y-m-d');
                        $dc->save();
                        session(['dc_interval'=>microtime(true)]);

                    }
                    else{
                        return view('w_date');
                    }

                }
            }
        }
        else{
            $dc=parametres::whereNom('dc')->first();
            if ($dc==null)
            {
                $dc=new parametres();
                $dc->nom='dc';
                $dc->valeur=date('Y-m-d');
                $dc->save();
                session(['dc_interval'=>microtime(true)]);
            }else{
                $d=date('Y-m-d');
                if($d>=$dc->valeur)
                {
                    $dc->valeur=date('Y-m-d');
                    $dc->save();
                    session(['dc_interval'=>microtime(true)]);

                }
                else{
                    return view('w_date');
                }

            }
        }

		return $next($request);
	}

}
