<?php namespace gesecole\Http\Controllers\comptabilite;

use gesecole\discipline_personnel;
use gesecole\fonction;
use gesecole\fonction_personnel;
use gesecole\heure_realise;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\parametres;
use gesecole\payement_salaire;
use gesecole\personnel;
use gesecole\salaire;
use gesecole\taux;
use gesecole\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class payementController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function __construct()
    {

        session(['big_title'=>trans('admin.comptabilite')]);
        $year=session('year');
        if($year==null){
            $p=parametres::whereNom('annee_scolaire')->get()->first();

            if(count($p)<=0){
                $p=new parametres();
                $p->nom='annee_scolaire';
                $p->valeur='2017-2018';
                $p->save();
                session(['year'=>$p->valeur]);
            }else{
                session(['year'=>$p->valeur]);
            }
        }
    }


    public function index()
	{
	   // \session(['success'=>16]);
        $titre=trans('admin.search_personnel');
        $values= array('titre'=>$titre);
        if(session('success')){
            $values['success']=\session('success');
            $ps=payement_salaire::find($values['success']);
            $p=personnel::find($ps->id_personnel);
            $s=salaire::whereIdPersonnel($ps->id_personnel)->first();
            $f=fonction_personnel::whereIdPersonnel($ps->id_personnel)->get();
            $fo=fonction::all();
            $u=User::find($ps->id_user);
            $values['payement']=$ps;
            $values['personnel']=$p;
            $values['fonctions']=$f;
            $values['salaire']=$s;
            $values['fonctions2']=$fo;
            $values['user']=$u;
            Session::forget('success');
        }
        return view('comptabilite/search_personnel',$values);
	}



    public function print_bulletin($id)
    {
        $titre=trans('admin.search_personnel');
        $values= array('titre'=>$titre);
            $values['success']=$id;
            $ps=payement_salaire::find($values['success']);
            $p=personnel::find($ps->id_personnel);
            $s=salaire::whereIdPersonnel($ps->id_personnel)->first();
            $f=fonction_personnel::whereIdPersonnel($ps->id_personnel)->get();
            $fo=fonction::all();
            $u=User::find($ps->id_user);
            $values['payement']=$ps;
            $values['personnel']=$p;
            $values['fonctions']=$f;
            $values['salaire']=$s;
            $values['fonctions2']=$fo;
            $values['user']=$u;
            Session::forget('success');

        return view('comptabilite/search_personnel',$values);
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{
        $p=personnel::find($id);
        $titre=trans('admin.payement').' '.$p->nom;

        $values= array('titre'=>$titre);

        $ps=payement_salaire::whereAnneeScolaire(\session('year'))
            ->where('id_personnel','=',$id)
            ->orderBy('date_p','desc')
            ->first();
        if($ps!= null){
            $values['dernier_payement']=$ps;
            $d=discipline_personnel::whereAnneeScolaire(\session('year'))
                ->where('id_personnel','=',$id)
                ->where('date_d','>=',$ps->date_p)
                ->get();
        }else
            {
                $d=discipline_personnel::whereAnneeScolaire(\session('year'))
                    ->where('id_personnel','=',$id)
                    ->get();
        }
        $hr=heure_realise::whereAnneeScolaire(\session('year'))
            ->where('id_personnel','=',$id)
            ->where('date_heure_realisee','>=',$ps?$ps->date_p:'1900-01-01')
            ->sum('nombre');

        $t=taux::whereIdPersonnel($id)->first();

        $values['discipline']=$d;
        $values['heure_realisee']=$hr;
        $values['taux']=$t;

        $s=salaire::whereIdPersonnel($id)->first();
        if($s!= null){
            $values['salaire']=$s;
        }
        $values['personnel']=$p;

        return view('comptabilite/create-payment',$values);


    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request,[
		   'id'=>'required|numeric',
            'heure_realisee'=>'required|numeric',
            'taux'=>'required|numeric',
            'total'=>'required|numeric',
            'mois'=>'required|numeric',
            'primes'=>'numeric',
            'acomptes'=>'numeric',
            'CNSS'=>'numeric',
            'cas_socials'=>'numeric',
            'assurances'=>'numeric',
            'autre_retenues'=>'numeric',
        ]);

		$id=$request->input('id');

        $heure_realisee=$request->input('heure_realisee');
        $taux=$request->input('taux');
        $total=$request->input('total');
        $mois=$request->input('mois');
        $primes=$request->input('primes');
        $acomptes=$request->input('acomptes');
        $CNSS=$request->input('CNSS');
        $cas_socials=$request->input('cas_socials');
        $assurances=$request->input('assurances');
        $autres_retenues=$request->input('autre_retenues');

        $total_retenu=$acomptes+$CNSS+$cas_socials+$assurances+$autres_retenues;

        $total=$total+$primes;

        $ps=new payement_salaire();
        $ps->id_personnel=$id;
        $ps->id_user=\Auth::user()->id;
        $ps->montant=floatval($total-$total_retenu);
        $ps->date_p=date('Y-m-d');
        $ps->mois=$mois;
        $ps->heure_realise=$heure_realisee;
        $ps->taux=$taux;
        $ps->total=floatval($total);
        $ps->primes=floatval($primes);
        $ps->acomptes=floatval($acomptes);
        $ps->cnss=floatval($CNSS);
        $ps->cas_sociales=floatval($cas_socials);
        $ps->assurances=floatval($assurances);
        $ps->autre_retenues=floatval($autres_retenues);
        $ps->total_retenu=floatval($total_retenu);
        $ps->net_a_payer=floatval($total-$total_retenu)   ;
        $ps->annee_scolaire=\session('year');
        $ps->save();
        $numero=sprintf('%08d',$ps->id);
        $ps->numero=$numero;

        $ps->save();

      
        session(['success'=>$ps->id]);
        return redirect()->action('comptabilite\payementController@index') ;


    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $p=personnel::find($id);
        $titre=trans('admin.payement').' '.$p->nom;
        $values= array('titre'=>$titre);
        $py=payement_salaire::whereAnneeScolaire(\session('year'))
            ->where('id_personnel','=',$id)
            ->get();
        $values['payements']=$py;
        $values['personnel']=$p;
        return view('comptabilite/liste_paiement',$values);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
