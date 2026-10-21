<?php namespace gesecole\Http\Controllers\personnel;

use gesecole\classe;
use gesecole\classeMatiere;
use gesecole\cycle;
use gesecole\heure_realise;
use gesecole\heuresPrevues;
use gesecole\niveau;
use gesecole\dispense;
use gesecole\horaire;
use gesecole\NNiveau;
use gesecole\salle;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use gesecole\Http\Requests;
use Illuminate\Support\Facades\DB;
use gesecole\Http\Controllers\Controller;
use gesecole\personnel;
use gesecole\matiere;
use gesecole\parametres;
use Illuminate\Http\Request;

class dispenseController extends Controller {


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	  public function __construct()
    {
        session(['big_title'=>trans('admin.gestion_personnel')]);
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

	public function index($id)
	{

        $m=dispense::whereIdPersonnel($id)->orderBy('id_classe')->orderBy('id_nniveau')->orderBy('id_matiere')->orderBy('jour')->get();
        $p=personnel::find($id);
        $titre=trans('admin.titre_table_dispense').' '.$p->nom;
        $n=classe::all();
        $hp=heuresPrevues::whereAnneeScolaire(\session('year'))
            ->where('id_personnel','=',$p->id)
            ->get();
        $hr=heure_realise::whereAnneeScolaire(\session('year'))
            ->where('id_personnel','=',$p->id)
            ->get();
        $e=matiere::orderBy('nom')->get();
        $nn=NNiveau::all();
        $s=salle::all();
        $h=horaire::orderBy('ordre')->get();
        $values= array('titre'=>$titre,'dispense'=>$m,'classes'=>$n,'matieres'=>$e,'horaires'=>$h);
        $values['nniveaux']=$nn;
        $values['heuresP']=$hp;
        $values['salles']=$s;
        $values['heuresR']=$hr;

        $values['personnel']=$p;
        if(session('success')){
            $values['success']=true;
            Session::forget('success');
        }

        return view('personnel/dispense',$values);
	}
    public function index1()
    {
        $titre=trans('admin.search_personnel');
        $c=classe::orderBy('nom')->get();
        $nn=NNiveau::all();
        $values= array('titre'=>$titre);
        $values['classes']=$c;
        $cy=cycle::all();
        $n=niveau::all();
        $values['cycles']=$cy;
        $values['nniveaux']=$nn;
        $values['niveaux']=$n;

        return view('personnel/dispenses',$values);
    }

    public function find(Request $req)
    {
        $pattern=$req->input('search');
        $p=DB::table('personnel')->whereRaw('lower(nom) LIKE lower(?)', ['%'.($pattern).'%'])
            ->orderBy('nom')
            ->paginate(30);
        $values= array('titre'=>trans('admin.search_personnel'));
        $c=classe::orderBy('nom')->get();

        $values['classes']=$c;
        $cy=cycle::all();
        $nn=NNiveau::all();
        $n=niveau::all();
        $values['cycles']=$cy;
        $values['niveaux']=$n;
        $values['nniveaux']=$nn;
        $values['personnel']=$p;
        session(['search_result'=>true]);

        return view('personnel/dispenses',$values);
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $req)
	{
        $titre=trans('admin.choix_horaire');
        $this->validate($req,[
            'id_matiere'=>'required|numeric',
            'id_personnel'=>'required|numeric',
            'id_classe'=>'required|numeric',
            'jour'=>'required|numeric',
            'nniveau'=>'required|numeric',
            'id_salle'=>'required|numeric',

        ]);
        $salle=$req->input('id_salle');
        $nniveau=$req->input('nniveau');

        $nn=NNiveau::find($nniveau);
        $cm=classeMatiere::whereIdClasse($req->input('id_classe'))
            ->where('id_cycle','=',$nn->id_cycle)
            ->where('id_nniveau','=',$nn->id)
            ->where('id_matiere','=',$req->input('id_matiere'))
            ->first();
        if($cm==null)
        {
            return redirect()->action('personnel\dispenseController@index',['id'=>$req->input('id_personnel')])->withErrors(['matiere'=>trans('admin.no_matiere_programme')]);
        }

        $h=horaire::where('type','=',0)->orderBy('ordre')->get();
        $oh=dispense::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$req->input('id_classe'))
            ->where('id_nniveau','=',$nniveau)
            ->where('jour','=',$req->input('jour'))
            ->get();


        $values['id_matiere']=$req->input('id_matiere');
        $values['id_personnel']=$req->input('id_personnel');
        $values['id_classe']=$req->input('id_classe');
        $values['id_nniveau']=$nniveau;
        $values['jour']=$req->input('jour');
        $values['horaires']=$h;
        $values['salle']=$salle;

        $values['id_salle']=$salle;

        $values['occupied_horaire']=$oh;
        $values['titre']=$titre;

        return view('personnel/create_dispense',$values);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request  $req)
	{
        $this->validate($req,[
            'id_matiere'=>'required',
            'id_personnel'=>'required',
            'id_classe'=>'required',
            'jour'=>'required',
            'id_nniveau'=>'required|numeric',
            'id_horaire'=>'required',
            'id_salle'=>'required',

        ]);
        $salle=$req->input('id_salle');

        $d=dispense::whereIdPersonnel($req->input('id_personnel'))
            ->where('jour','=',$req->input('jour'))
            ->where('id_horaire','=',$req->input('id_horaire'))
            ->where('annee_scolaire','=',\session('year'))
            ->first();

        if($d!=null)
        {

            return redirect()->action('personnel\dispenseController@index',['id'=>$req->input('id_personnel')])->withErrors(['jour'=>trans('admin.heure_prise')]);
        }

        $c=new dispense();
        $c->annee_scolaire=session('year');
        $c->id_matiere=$req->input('id_matiere');
        $c->id_personnel=$req->input('id_personnel');
        $c->id_nniveau=$req->input('id_nniveau');
        $c->id_classe=$req->input('id_classe');
        $c->jour=$req->input('jour');
        $c->id_salle=$salle;
        $c->id_horaire=$req->input('id_horaire');
        $c->save();

        $hp=heuresPrevues::whereAnneeScolaire(\session('year'))
            ->where('id_personnel','=',$req->input('id_personnel'))
            ->where('id_classe','=',$req->input('id_classe'))
            ->where('id_nniveau','=',$req->input('id_nniveau'))
            ->where('id_matiere','=',$req->input('id_matiere'))
            ->first();
        if($hp==null){
            $hp=new heuresPrevues();
            $hp->id_personnel=$req->input('id_personnel');
            $hp->id_classe=$req->input('id_classe');
            $hp->id_nniveau=$req->input('id_nniveau');
            $hp->id_matiere=$req->input('id_matiere');
            $hp->annee_scolaire=\session('year');
            $hp->save();
        }


        session(['success'=>true]);
        return redirect()->action('personnel\dispenseController@index',['id'=>$c->id_personnel]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request)
	{
         $this->validate($request,[
            "nniveau"=>"required|numeric",
        ]);
	    $id=$request->input('id');
	    $nniveau=$request->input('nniveau');
	    $nn=NNiveau::find($nniveau);
        $titre=trans('admin.emploi_temps_classe');
        $d=dispense::whereAnneeScolaire(\session('year'))
        ->where('id_classe','=',$id)
            ->where('id_nniveau','=',$nniveau)
        ->get();
        $c=classe::find($id);
        $s=salle::orderBy('nom')->get();
        $h=horaire::orderBy('ordre')->get();
        $p=personnel::whereType(0)->orderBy('nom')->get();
        $m=matiere::join('classe_matiere','matiere.id','=','classe_matiere.id_matiere')
            ->where('id_classe','=',$id)
            ->where('id_nniveau','=',$nniveau)
            ->where('id_cycle','=',$nn->id_cycle)
            ->select(['matiere.*'])
            ->orderBy((App::getLocale()=='en')?'nom':'nom_anglais')
            ->get()
        ;
        $values['titre']=$titre;
        $values['dispenses']=$d;
        $values['horaires']=$h;
        $values['nniveau']=NNiveau::find($nniveau);
        $values['classe']=$c;
        $values['salles']=$s;
        $values['personnels']=$p;
        $values['matieres']=$m;


        return view('personnel/emploi_temps',$values);

    }
    public function edit_timetable(Request  $req,$id)
    {
        $this->validate($req,[
            'matiere'=>'required',
            'personnel'=>'required',
            'jour'=>'required',
            "nniveau"=>"required|numeric",
            'id_horaire'=>'required',
            'salle'=>'numeric'

        ]);
        $disp=dispense::whereAnneeScolaire(\session('year'))
            ->where('id_classe','=',$id)
            ->where('jour','=',$req->input('jour'))
            ->where('id_nniveau','=',$req->input('nniveau'))
            ->where('id_horaire','=',$req->input('id_horaire'))
            ->first();
        if($disp!=null){
            $disp->delete();
        }
        $disp=dispense::whereAnneeScolaire(\session('year'))
            ->where('jour','=',$req->input('jour'))
            ->where('id_personnel','=',$req->input('personnel'))
            ->where('id_horaire','=',$req->input('id_horaire'))
            ->first();
        if($disp!=null){
            $disp->delete();
        }

        $c=new dispense();
        $c->annee_scolaire=session('year');
        $c->id_matiere=$req->input('matiere');
        $c->id_personnel=$req->input('personnel');
        $c->id_classe=$id;
        $c->id_salle=$req->input('salle');
        $c->id_nniveau=$req->input('nniveau');
        $c->jour=$req->input('jour');
        $c->id_horaire=$req->input('id_horaire');
        $c->save();
        $hp=heuresPrevues::whereAnneeScolaire(\session('year'))
            ->where('id_personnel','=',$req->input('personnel'))
            ->where('id_classe','=',$id)
            ->where('id_nniveau','=',$req->input('nniveau'))
            ->where('id_matiere','=',$req->input('matiere'))
            ->first();
        if($hp==null){
            $hp=new heuresPrevues();
            $hp->id_personnel=$req->input('personnel');
            $hp->id_classe=$id;
            $hp->id_nniveau=$req->input('nniveau');
            $hp->id_matiere=$req->input('matiere');
            $hp->annee_scolaire=\session('year');
            $hp->save();
        }

        session(['success'=>true]);
        return redirect()->to('/personnel/emploi-temps/show/'.'?id='.$id.'&nniveau='.$req->input('nniveau'));
    }

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $req)
	{
        $this->validate($req,[
            'id'=>'required',
            'jour'=>'required',
            'id_horaire'=>'required',
            'id_salle'=>'required',

        ]);
        $salle=$req->input('id_salle');
        $d1=dispense::find($req->input('id'));
        $d=dispense::whereAnneeScolaire(\session('year'))
            ->where('id','<>',$d1->id)
            ->where('jour','=',$req->input('jour'))
            ->where('id_horaire','=',$req->input('id_horaire'))
            ->where('id_classe','=',$d1->id_classe)
            ->where('id_nniveau','=',$d1->id_nniveau)
            ->first();

        if($d!=null)
        {

            return redirect()->action('personnel\dispenseController@index',['id'=>$d1->id_personnel])->withErrors(['jour'=>trans('admin.heure_prise2')]);
        }

        $d1->jour=$req->input('jour');
        $d1->id_salle=$salle;
        $d1->id_horaire=$req->input('id_horaire');
        $d1->save();




        session(['success'=>true]);
        return redirect()->action('personnel\dispenseController@index',['id'=>$d1->id_personnel]);

    }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $req,$id)
	{
        $this->validate($req, [
            'id_matiere' => 'required|numeric',
            'id_personnel' => 'required|numeric',
            'id_classe' => 'required|numeric',
            'annee_scolaire' => 'required'
        ]);

        $c = dispense::find($id);
        $c->id_matiere = $req->input('id_matiere');
        $c->id_personnel = $req->input('id_personnel');
        $c->id_classe = $req->input('id_classe');
        $c->annee_scolaire = $req->input('annee_scolaire');
        $c->save();
        session(['success' => true]);
        return redirect()->action('personnel\dispenseController@index');
	}

	public function edit_heure_prevue()
    {

    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
      $fp=dispense::find($id);
        $id_p=$fp->id_personnel;
        $fp->delete();
        session(['success'=>true]);
        return redirect()->action('personnel\dispenseController@index',['id'=>$id_p]);
	}

}
