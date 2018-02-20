<?php namespace gesecole\Http\Controllers\personnel;

use gesecole\classe;
use gesecole\cycle;
use gesecole\niveau;
use gesecole\dispense;
use gesecole\horaire;
use gesecole\NNiveau;
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

        $m=dispense::whereIdPersonnel($id)->get();
        $p=personnel::find($id);
        $titre=trans('admin.titre_table_dispense').' '.$p->nom;
        $n=classe::all();
        $e=matiere::all();
        $nn=NNiveau::all();
        $h=horaire::all();
        $values= array('titre'=>$titre,'dispense'=>$m,'classes'=>$n,'matieres'=>$e,'horaires'=>$h);
        $values['nniveaux']=$nn;
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
        ]);
        $nniveau=$req->input('nniveau');
        $h=horaire::orderBy('ordre')->get();
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

        ]);
        $c=new dispense();
        $c->annee_scolaire=session('year');
        $c->id_matiere=$req->input('id_matiere');
        $c->id_personnel=$req->input('id_personnel');
        $c->id_nniveau=$req->input('id_nniveau');
        $c->id_classe=$req->input('id_classe');
        $c->jour=$req->input('jour');
        $c->id_horaire=$req->input('id_horaire');
        $c->save();
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
        $titre=trans('admin.emploi_temps_classe');
        $d=dispense::whereAnneeScolaire(\session('year'))
        ->where('id_classe','=',$id)
            ->where('id_nniveau','=',$nniveau)
        ->get();
        $c=classe::find($id);
        $h=horaire::orderBy('ordre')->get();
        $p=personnel::whereType(0)->get();
        $m=matiere::all();
        $values['titre']=$titre;
        $values['dispenses']=$d;
        $values['horaires']=$h;
        $values['nniveau']=NNiveau::find($nniveau);
        $values['classe']=$c;
        $values['personnels']=$p;
        $values['matieres']=$m;


        return view('personnel/emploi_temps',$values);

    }

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

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
