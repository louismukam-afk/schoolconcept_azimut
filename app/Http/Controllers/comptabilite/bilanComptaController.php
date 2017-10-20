<?php namespace gesecole\Http\Controllers\comptabilite;

use gesecole\decaissement;
use gesecole\eleve;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\payement;
use gesecole\payement_salaire;
use gesecole\personnel;
use Illuminate\Support\Facades\Session;
use gesecole\parametres;
use Illuminate\Http\Request;

class bilanComptaController extends Controller {

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

	public function index(Request $request)
	{
        $this->validate($request,[
           'date_debut'=>'date',
            'date_fin'=>'date',
        ]);
        $titre=trans('admin.bilan_entrees');

        $date_debut=$request->input('date_debut');
        $date_fin=$request->input('date_fin');

        if($date_debut!=null && $date_fin!=null){
            $p=payement::whereAnneeScolaire(\session('year'))
                ->where('date_payement','>=',(new \DateTime($date_debut))->format('Y-m-d'))
                ->where('date_payement','<=',(new \DateTime($date_fin))->format('Y-m-d'))
                ->orderBy('date_payement','desc')
                ->paginate(30);
            $p2=payement::whereAnneeScolaire(\session('year'))
                ->where('date_payement','>=',(new \DateTime($date_debut))->format('Y-m-d'))
                ->where('date_payement','<=',(new \DateTime($date_fin))->format('Y-m-d'))
                ->get();


        }else{
            $p=payement::whereAnneeScolaire(\session('year'))->orderBy('date_payement','desc')->paginate(30);
            $p2=payement::whereAnneeScolaire(\session('year'))->get();
        }



        $total=0;
        foreach ($p2 as $pay){
            $total+=$pay->montant;
        }
        $values= array('titre'=>$titre,'payements'=>$p,'total'=>$total);
        if($date_debut!=null && $date_fin!=null)
        {
            $values['date_debut']=$date_debut;
            $values['date_fin']=$date_fin;
        }
        if(session('success')){
            $values['success']=true;
            Session::forget('success');
        }


        return view('comptabilite\bilanEntrees',$values);
	}

    public function indexSorties(Request $request)
    {
        $this->validate($request,[
            'date_debut'=>'date',
            'date_fin'=>'date',
        ]);
        $titre=trans('admin.bilan_sorties');

        $date_debut=$request->input('date_debut');
        $date_fin=$request->input('date_fin');

        if($date_debut!=null && $date_fin!=null){
            $p=payement_salaire::whereAnneeScolaire(\session('year'))
                ->where('date_p','>=',(new \DateTime($date_debut))->format('Y-m-d'))
                ->where('date_p','<=',(new \DateTime($date_fin))->format('Y-m-d'))
                ->orderBy('date_p','desc')
                ->paginate(30);
            $p2=payement_salaire::whereAnneeScolaire(\session('year'))
                ->where('date_p','>=',(new \DateTime($date_debut))->format('Y-m-d'))
                ->where('date_p','<=',(new \DateTime($date_fin))->format('Y-m-d'))
                ->get();

            $d=decaissement::whereAnneeScolaire(\session('year'))
                ->where('date','>=',(new \DateTime($date_debut))->format('Y-m-d'))
                ->where('date','<=',(new \DateTime($date_fin))->format('Y-m-d'))
                ->orderBy('date','desc')
                ->paginate(30);
            $d2=decaissement::whereAnneeScolaire(\session('year'))
                ->where('date','>=',(new \DateTime($date_debut))->format('Y-m-d'))
                ->where('date','<=',(new \DateTime($date_fin))->format('Y-m-d'))
                ->get();


        }else{
            $p=payement_salaire::whereAnneeScolaire(\session('year'))->orderBy('date_p','desc')->paginate(30);
            $p2=payement_salaire::whereAnneeScolaire(\session('year'))->get();

            $d=decaissement::whereAnneeScolaire(\session('year'))->orderBy('date','desc')->paginate(30);
            $d2=decaissement::whereAnneeScolaire(\session('year'))->get();
        }



        $total_p=0;
        foreach ($p2 as $pay){
            $total_p+=$pay->net_a_payer;
        }
        $total_d=0;
        foreach ($d2 as $pay){
            $total_d+=$pay->montant;
        }
        $pers=personnel::all();
        $values= array('titre'=>$titre,'payements'=>$p,'total'=>$total_p,'total_d'=>$total_d,'decaissements'=>$d,'personnels'=>$pers);
        if($date_debut!=null && $date_fin!=null)
        {
            $values['date_debut']=$date_debut;
            $values['date_fin']=$date_fin;
        }
        if(session('success')){
            $values['success']=true;
            Session::forget('success');
        }


        return view('comptabilite\bilanSorties',$values);
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
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
