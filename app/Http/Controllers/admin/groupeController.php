<?php namespace gesecole\Http\Controllers\admin;

use gesecole\classe;
use gesecole\classeMatiere;
use gesecole\groupeMatieres;
use gesecole\Http\Controllers\Controller;
use gesecole\matiere;
use gesecole\NNiveau;
use gesecole\parametres;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class groupeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function __construct()
    {
        session(['big_title'=>trans('admin.dashboard')]);
        $this->renewSchoolYear();
    }

	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
    public function create($id)
    {
        $cm=classeMatiere::find($id);
        $nniveau=$cm->id_nniveau;
        $m=$cm->id_matiere;
        $c=classe::find($cm->id_classe);
        $nn=NNiveau::find($nniveau);
        $cycle= $cm->id_cycle;
        $titre=trans('admin.gestion_groupe').' : '.((session('locale')=='en')?$c->nom_anglais:$c->nom).' - '.$nn->nom;

        //AND cm.id IN (SELECT id_classe_matiere FROM groupe_matieres)
        $g=DB::select('SELECT  cm.id,cm.id_matiere,cm.id_classe,cm.type,cm.id_cycle,m.nom_anglais,m.nom as nom_matiere, g.id AS id_groupe,g.semestre FROM classe_matiere cm, matiere m ,classe c,groupe_matieres g WHERE cm.id_classe=c.id And c.id=? AND cm.id_matiere=m.id   AND cm.id_cycle =? AND g.id_classe_matiere=cm.id AND g.id_matiere_mere=? AND cm.id_nniveau=? ',[$cm->id_classe,$cycle,$m,$nniveau]);
        $p=DB::select('SELECT cm.id,cm.id_matiere,cm.type,cm.id_cycle,cm.semestre,m.nom_anglais,m.nom as nom_matiere,coef,max FROM classe_matiere cm, matiere m ,classe c WHERE cm.id_classe=c.id AND cm.id_matiere=m.id AND cm.id_nniveau=? And c.id=?  AND cm.id_cycle=? AND cm.id <> ? AND cm.id NOT IN (SELECT id_classe_matiere FROM groupe_matieres)',[$nniveau,$cm->id_classe,$cycle,$id]);

        $values= array('titre'=>$titre);
        $values['groupes']=$g;
        $values['matieres']=$p;
        $values['matiere_mere']=matiere::find($m);
        $values['id_cycle']=$cycle;
        $values['classe']=$c;
        $values['classeMatiere']=$cm;
        if(session('success')){
            Session::forget('success');
            $values['success']=true;

        }

        return view('admin\addGroupe',$values);
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

        ]);
        $id=$request->input('id');
        $cm=classeMatiere::find($id);
        $matiereList=$request->input('matiere');
        $semestreList=$request->input('semestre');
        $i=0;
        foreach ($matiereList as $mat){
            $gm=new groupeMatieres();
            $gm->id_classe_matiere=$mat;
            $gm->id_matiere_mere=$cm->id_matiere;
            $gm->id_classe=$cm->id_classe;
            $gm->id_nniveau=$cm->id_nniveau;
            $gm->semestre=$semestreList[$i];
            $gm->save();

            $i++;
        }

        session(['success'=>true]);

        //$request->request->add(['cycle'=>$id_cycle]);
        return redirect()->to(url('/admin/programmes/groupe/'.$id));
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
	public function destroy(Request $request,$id)
	{
		$id_classe_matiere=$request->input('id_classe_matiere');
		groupeMatieres::destroy($id);

        session(['success'=>true]);

        //$request->request->add(['cycle'=>$id_cycle]);
        return redirect()->to(url('/admin/programmes/groupe/'.$id_classe_matiere));
	}

}
