<?php namespace gesecole\Http\Controllers\personnel;

use gesecole\dispense;
use gesecole\heure_realise;
use gesecole\heuresPrevues;
use gesecole\horaire;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\parametres;
use gesecole\taux;
use Illuminate\Support\Facades\Session;
use gesecole\matiere;
use gesecole\personnel;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class presenceController extends Controller {

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
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$titre=trans('admin.search_personnel');
		$values['titre']=$titre;
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }
		return view('personnel/search_personnel_presence',$values);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{

        $p=personnel::find($id);
        $h=heure_realise::whereAnneeScolaire(\session('year'))
            ->where('id_personnel','=',$id)
            ->orderBy('date_heure_realisee','desc')
            ->paginate(30);


        $d=dispense::whereAnneeScolaire(\session('year'))->where('id_personnel','=',$id)->get();
        $ho=horaire::orderBy('ordre')->get();

        $values=array('titre'=>trans('admin.compte_heure_de').' '.$p->nom);
        if(session('success')){
            $values['success']=true;
            Session::forget('success');
        }
		$values['personnel']=$p;
        $values['dispenses']=$d;
        $values['horaires']=$ho;
        $values['realise']=$h;


        return view('personnel/updatePresence',$values);
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
	public function update(Request $request,$id)
	{
        $this->validate($request,[
            'nombre'=>'numeric|required',
            'date'=>'date'
        ]);
        $dispense=$request->input('dispense');
        $nombre=$request->input('nombre');
        $date=$request->input('date');

        $h=new heure_realise();

        if($dispense==0){
           $t=taux::whereIdPersonnel($id)
               ->where('id_matiere','=',0)
               ->where('id_classe','=',0)
               ->where('id_nniveau','=',0)
               ->first();

           if($t==null)
           {
            $taux=0;
           }
           else
           {
               $taux=$t->taux;
           }
        }else{
            $d=dispense::find($dispense);
            $h->id_classe=$d->id_classe;
            $h->id_nniveau=$d->id_nniveau;
            $h->id_matiere=$d->id_matiere;
            $ho=horaire::find($d->id_horaire);
            $t=taux::whereIdPersonnel($id)
                ->where('id_matiere','=',$d->id_matiere)
                ->where('id_classe','=',$d->id_classe)
                ->where('id_nniveau','=',$d->id_nniveau)
                ->first();

            if($t==null)
            {
                $t=taux::whereIdPersonnel($id)
                    ->where('id_matiere','=',0)
                    ->where('id_classe','=',0)
                    ->where('id_nniveau','=',0)
                    ->first();
                if($t==null)
                {
                    $taux=0;
                }
                else
                {
                    $taux=$t->taux;
                }
            }
            else
            {
                $taux=$t->taux;
            }


            $h->jour=$d->jour;
            $h->intervalle=$ho->intervalle;
        }

        $h->taux=$taux;
        $h->id_personnel=$id;
        if($date!=null)
            $h->date_heure_realisee=$date;
        else
            $h->date_heure_realisee=date('Y-m-d');

        $h->date_enregistrement=date('Y-m-d');

        $h->annee_scolaire=\session('year');
        $h->nombre=$nombre;

        $current_user = \Auth::user();
        $h->id_creator = $current_user->id;
        $h->id_last_editor = $current_user->id;

        $h->save();
        session(['success'=>true]);
        return redirect()->action('personnel\presenceController@create',['id'=>$id]);
	}

	public function update_heure_prevue(Request $request)
    {
        $personnel=$request->input('personnel');
        $classe=$request->input('classe');
        $nniveau=$request->input('nniveau');
        $matiere=$request->input('matiere');
        $heure=$request->input('heure');


        $hp=heuresPrevues::whereAnneeScolaire(\session('year'))
            ->where('id_personnel','=',$personnel)
            ->where('id_classe','=',$classe)
            ->where('id_nniveau','=',$nniveau)
            ->where('id_matiere','=',$matiere)
            ->first();
        if($hp!=null){
            $hp->total=$heure;
            $hp->save();
        }
            else{
            $hp=new heuresPrevues();
            $hp->id_personnel=$personnel;
            $hp->id_classe=$classe;
            $hp->id_nniveau=$nniveau;
            $hp->id_matiere=$matiere;
            $hp->annee_scolaire=\session('year');
                $hp->total=$heure;
                $hp->save();
            }


        return response()->json('OK');


    }
    public function show_heure_prevue(Request $request)
    {
        $personnel=$request->input('personnel');
        $classe=$request->input('classe');
        $nniveau=$request->input('nniveau');
        $matiere=$request->input('matiere');

        $hp=heuresPrevues::whereAnneeScolaire(\session('year'))
            ->where('id_personnel','=',$personnel)
            ->where('id_classe','=',$classe)
            ->where('id_nniveau','=',$nniveau)
            ->where('id_matiere','=',$matiere)->first();
        $hp?$t=$hp->total:$t=0;


        return response()->json($t);


    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request,$id)
	{
	    $id_p=$request->input('personnel');
		$h=heure_realise::find($id);
		$h->delete();
        session(['success'=>true]);
        return redirect()->action('personnel\presenceController@create',['id'=>$id_p]);

	}

}
