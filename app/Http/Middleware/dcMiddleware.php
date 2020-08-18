<?php namespace gesecole\Http\Middleware;

use Closure;
use gesecole\parametres;
use Illuminate\Support\Facades\Session;

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
            if ($i>=600)
            {
                $dc=parametres::whereNom('dc')->first();
                if ($dc==null)
                {
                    $dc=new parametres();
                    $dc->nom='dc';
                    $dc->valeur=date('Y-m-d H:i:s');
                    $dc->save();
                    Session::remove('dc_interval');
                    session(['dc_interval'=>microtime(true)]);
                }else{

                    $d=date('Y-m-d H:i:s');
                    if($d>=$dc->valeur)
                    {
                        //dump($i.' '.$d.' '.$dc->valeur);


                        $dc->valeur=$d;
                        $dc->save();

                        Session::remove('dc_interval');

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

                $dc->valeur=date('Y-m-d H:i:s');
                $dc->save();

                Session::remove('dc_interval');

                session(['dc_interval'=>microtime(true)]);
            }else{
                $d=date('Y-m-d H:i:s');
                if($d>=$dc->valeur)
                {
                    $dc->valeur=$d;
                    $dc->save();
                    Session::remove('dc_interval');

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
