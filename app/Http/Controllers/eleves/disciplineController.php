<?php namespace gesecole\Http\Controllers\eleves;

use gesecole\discipline;
use gesecole\eleve;
use Illuminate\Support\Facades\Session;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class disciplineController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function __construct()
    {
        session(['big_title'=>trans('eleve.big_title')]);
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
        $values= array('titre'=>trans('inscription.title_search'));
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }

        return view('eleves\search',$values);
	}

    public function search(Request $request)
    {
        $pattern=$request->input('search');
        $e=DB::select('SELECT e.matricule,e.id, e.nom,e.prenom,e.date_naiss,e.lieu_naiss,e.sexe,c.nom as nom_classe FROM eleve e,inscription i,classe c WHERE (e.nom LIKE ? OR e.prenom LIKE ? OR e.matricule LIKE ?) AND e.id=i.id_eleve AND i.id_classe= c.id AND annee_scolaire=?',['%'.$pattern.'%','%'.$pattern.'%','%'.$pattern.'%',session('year')]);
        $values= array('titre'=>trans('inscription.title_search'));
        $values['eleves']=$e;
        session(['search_result'=>true]);

        return view('eleves\search',$values);
    }
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{
	    $e=eleve::find($id);
        $values= array('titre'=>trans('eleve.titre_discipline'));
        $d=discipline::whereAnneeScolaire(session('year'))->where('id_eleve','=',$id)->first();
        $values['eleve']=$e;
        if($d==null){
            $d=new discipline();
            $d->id_eleve=$id;
            $d->annee_scolaire=session('year');
            $d->save();
        }
        $values['discipline']=$d;

        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }

        return view('eleves\discipline',$values);
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
	public function update(Request $request)

	{
		$this->validate($request,[
		   'id'=>'required|numeric',
            'retard'=>'required|numeric|min:0',
            'abscence_inj'=>'required|numeric|min:0',
            'abscence_just'=>'required|numeric|min:0',
            'avertissement'=>'required|numeric|min:0',
            'blame'=>'required|numeric|min:0',
            'exclusion'=>'required|numeric|min:0',
        ]);
		$d=discipline::whereIdEleve($request->input('id'))->where('annee_scolaire','=',session('year'))->first();
		$d->retard=$request->input('retard');
        $d->abscence_inj=$request->input('abscence_inj');
        $d->abscence_just=$request->input('abscence_just');
        $d->avertissement=$request->input('avertissement');
        $d->blame=$request->input('blame');
        $d->exclusion=$request->input('exclusion');
        $d->save();
        session(['success'=>true]);
        return redirect()->action('eleves\disciplineController@create',[$request->input('id')]);
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
