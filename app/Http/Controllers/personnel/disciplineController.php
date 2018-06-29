<?php namespace gesecole\Http\Controllers\personnel;

use gesecole\discipline;
use gesecole\discipline_personnel;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;

use gesecole\personnel;
use gesecole\titulaire;
use Illuminate\Http\Request;
use gesecole\parametres;
use Illuminate\Support\Facades\Session;

class disciplineController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

    public function __construct()
    {

        session(['big_title'=>trans('menu.gestion_personnel')]);
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
        $titre=trans('admin.search_personnel');
        $values= array('titre'=>$titre);
        return view('personnel/search_discipline_personnel',$values);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */



    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($id)
    {
        $e=personnel::find($id);
        $values= array('titre'=>trans('eleve.titre_discipline'));
        $values['pers']=$e;

        $d=discipline::whereAnneeScolaire(session('year'))->where('id_personnel','=',$id)->get();
        $abscence_inj=$d->where('type',0,false)
            ->where('just','0',false)
            ->sum('nb_heures');
        $abscence_just=$d->where('type',0,false)
            ->where('just','1',false)
            ->sum('nb_heures');
        $retard=$d->where('type',1,false)
            ->count();
        $avertissement=$d->where('type',3,false)
            ->count();
        $blame=$d->where('type',4,false)
            ->count();
        $exclusion=$d->where('type',2,false)
            ->sum('nb_jours');
        $exclu_def=$d->where('type',5,false)
            ->first();
        $values['abscence_just']=$abscence_just;
        $values['abscence_inj']=$abscence_inj;
        $values['retard']=$retard;
        $values['avertissement']=$avertissement;
        $values['blame']=$blame;
        $values['exclusion']=$exclusion;
        if($exclu_def!=null)
            $values['exclus_def']=$exclu_def;







        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }

        /*
        $d=discipline::whereAnneeScolaire(session('year'))->where('id_eleve','=',$id)->first();
        if($d==null){
            $d=new discipline();
            $d->id_eleve=$id;
            $d->annee_scolaire=session('year');
            $d->save();
        }
        $values['discipline']=$d;


        */

        return view('personnel/discipline',$values);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request,$id)
    {
        $type=$request->input('type');
        $just=$request->input('just');
        $e=personnel::find($id);
        $d=null;
        if($type==0) {
            $d = discipline::whereAnneeScolaire(session('year'))
                ->where('id_personnel', '=', $id)
                ->where('type', '=', $type)
                ->where('just', '=', $just)
                ->orderBy('ddate','desc')
                ->get();
        }else
        {

            $d = discipline::whereAnneeScolaire(session('year'))
                ->where('id_personnel', '=', $id)
                ->where('type', '=', $type)
                ->orderBy('ddate','desc')
                ->get();
        }
        return response()->json($d);

    }

    public function justify(Request $request)
    {
        $justify=$request->input('justify');
        foreach ($justify as $j)
        {
            $jst=discipline::find($j);
            $jst->just=1;
            $jst->save();
        }

        return response('',200);
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
            'type'=>'required|numeric',
            "nb_heures"=>"numeric",
            "nb_jours"=>"numeric",
        ]);
        $id=$request->input('id');
        $type=$request->input('type');
        $nb_heures=$request->input('nb_heures');
        $nb_jours=$request->input('nb_jours');
        $desc=$request->input('description');
        $date=$request->input('date');
        $sequence=$request->input('sequence');

        $d=new discipline();
        $d->id_personnel=$id;
        $d->type=$type;
        $d->description=$desc;
        $d->annee_scolaire=\session('year');
        if($date==null)
            $d->ddate=date('Y-m-d');
        else
            $d->ddate=$date;
        if($sequence!=null)
            $d->sequence=$sequence;
        if($type==0)
            $d->nb_heures=$nb_heures;
        if($type==2)
            $d->nb_jours=$nb_jours;
        if($type==5)
            $d->exclus_def=1;


        $d->save();
        session(['success'=>true]);
        return redirect()->action('personnel\disciplineController@create',[$id]);
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
