<?php namespace gesecole\Http\Controllers\personnel;

use gesecole\CoursEnseignant;
use gesecole\FicheProgression;
use gesecole\Http\Controllers\Controller;
use gesecole\classe;
use gesecole\cycle;
use gesecole\niveau;
use gesecole\NNiveau;
use gesecole\personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;

class ficheProgressionController extends Controller {

    public function __construct()
    {
        session(array('big_title' => trans('menu.gestion_personnel')));
        $this->renewSchoolYear();
    }

    public function index()
    {
        return redirect()->route('fiches_progression_enseignants');
    }

    public function enseignants(Request $request)
    {
        $annee=$request->input('annee_scolaire',session('year'));
        $search=trim($request->input('q'));
        return view('personnel.fiches_progression.enseignants',array(
            'titre'=>'Fiches de progression - Enseignants',
            'enseignants'=>$this->enseignantsQuery($search)->paginate(30),
            'years'=>$this->years(), 'selectedYear'=>$annee, 'search'=>$search
        ));
    }

    public function coursEnseignant(Request $request,$id)
    {
        $annee=$request->input('annee_scolaire',session('year'));
        $enseignant=$this->enseignantsQuery()->where('id','=',$id)->first();
        if(!$enseignant) abort(404);
        $cours=CoursEnseignant::with(array('personnel','cycle','filiere','nniveau','classe','matiere'))
            ->where('annee_scolaire','=',$annee)->where('id_personnel','=',$enseignant->id)
            ->orderBy('id_classe')->orderBy('id_nniveau')->orderBy('semestre')->get();
        $fiches=$this->fichesParCours($annee,$enseignant->id);
        $groupes=array();
        foreach($cours as $item){
            $specialite=$item->classe?$item->classe->nom:'Specialite non renseignee';
            $niveau=$item->nniveau?$item->nniveau->nom:'Niveau non renseigne';
            $key=$specialite.' / '.$niveau;
            if(!isset($groupes[$key])) $groupes[$key]=array();
            $groupes[$key][]=$item;
        }
        return view('personnel.fiches_progression.cours_enseignant',array(
            'titre'=>'Ajouter une fiche de progression','enseignant'=>$enseignant,
            'groupes'=>$groupes,'fichesParCours'=>$fiches,'years'=>$this->years(),'selectedYear'=>$annee
        ));
    }

    public function fichesEnseignant(Request $request,$id)
    {
        $annee=$request->input('annee_scolaire',session('year'));
        $enseignant=$this->enseignantsQuery()->where('id','=',$id)->first();
        if(!$enseignant) abort(404);
        $fiches=FicheProgression::with(array('personnel','cours.matiere','cours.classe','cours.nniveau'))
            ->where('annee_scolaire','=',$annee)->where('id_campus','=',$this->currentCampusId())
            ->where('id_personnel','=',$enseignant->id)->orderBy('created_at','desc')->get();
        return view('personnel.fiches_progression.fiches_enseignant',array(
            'titre'=>'Fiches de progression','enseignant'=>$enseignant,'fiches'=>$fiches,
            'years'=>$this->years(),'selectedYear'=>$annee
        ));
    }

    public function parSpecialite(Request $request){ return $this->specialiteView($request,true); }
    public function manquantes(Request $request){ return $this->specialiteView($request,false); }

    public function store(Request $request)
    {
        $this->validate($request,array(
            'id_personnel'=>'required|numeric','id_cours_enseignant'=>'required|numeric',
            'annee_scolaire'=>'required','fiche'=>'required|mimes:pdf,doc,docx|max:20480'
        ));
        $cours=CoursEnseignant::where('id','=',$request->input('id_cours_enseignant'))
            ->where('id_personnel','=',$request->input('id_personnel'))
            ->where('annee_scolaire','=',$request->input('annee_scolaire'))->first();
        if(!$cours) return redirect()->back()->withErrors(array('id_cours_enseignant'=>'Ce cours ne correspond pas a cet enseignant pour cette annee academique.'))->withInput();
        $enseignant=$this->enseignantsQuery()->where('id','=',$request->input('id_personnel'))->first();
        if(!$enseignant) abort(404);
        $file=$request->file('fiche');
        if(!$file || !$file->isValid()) return redirect()->back()->withErrors(array('fiche'=>'Fichier invalide.'))->withInput();
        $extension=strtolower($file->getClientOriginalExtension());
        $originalName=$file->getClientOriginalName();
        $mime=$file->getClientMimeType();
        $storageDir=storage_path('app/fiches_progression/'.$request->input('annee_scolaire'));
        if(!File::exists($storageDir)) File::makeDirectory($storageDir,0755,true);
        $storedName=date('YmdHis').'_'.uniqid().'_'.$request->input('id_personnel').'_'.$request->input('id_cours_enseignant').'.'.$extension;
        $file->move($storageDir,$storedName);
        FicheProgression::create(array(
            'id_personnel'=>$request->input('id_personnel'),'id_cours_enseignant'=>$request->input('id_cours_enseignant'),
            'annee_scolaire'=>$request->input('annee_scolaire'),'id_campus'=>$this->currentCampusId(),
            'titre'=>$request->input('titre'),'description'=>$request->input('description'),
            'fichier_original'=>$originalName,'fichier_path'=>'app/fiches_progression/'.$request->input('annee_scolaire').'/'.$storedName,
            'fichier_extension'=>$extension,'fichier_mime'=>$mime,'id_user'=>Auth::user()?Auth::user()->id:null
        ));
        session(array('success'=>true));
        return $request->input('redirect_to')?redirect()->to($request->input('redirect_to')):redirect()->route('fiches_progression_enseignant_fiches',array($enseignant->id,'annee_scolaire'=>$request->input('annee_scolaire')));
    }

    public function show($id){ return $this->fileResponse($id,false); }
    public function download($id){ return $this->fileResponse($id,true); }

    public function destroy($id)
    {
        $fiche=$this->findForCampus($id);
        if($fiche){
            $path=storage_path($fiche->fichier_path);
            if(File::exists($path)) File::delete($path);
            $fiche->delete();
            session(array('success'=>true));
        }
        return redirect()->back();
    }

    private function fileResponse($id,$download)
    {
        $fiche=$this->findForCampus($id);
        if(!$fiche) abort(404);
        $path=storage_path($fiche->fichier_path);
        if(!File::exists($path)) abort(404);
        if($download) return Response::download($path,$fiche->fichier_original);
        $mime=$fiche->fichier_mime?$fiche->fichier_mime:($fiche->fichier_extension==='pdf'?'application/pdf':'application/octet-stream');
        return Response::make(File::get($path),200,array('Content-Type'=>$mime,'Content-Disposition'=>'inline; filename="'.str_replace('"','',$fiche->fichier_original).'"'));
    }

    private function findForCampus($id)
    {
        return FicheProgression::where('id','=',$id)->where('id_campus','=',$this->currentCampusId())->first();
    }

    private function enseignantsQuery($search=null)
    {
        $query=personnel::where('id_campus','=',$this->currentCampusId())->where(function($q){
            if(Schema::hasColumn('personnel','type_personnel')) $q->whereIn('type_personnel',array('enseignant','vacataire'));
            elseif(Schema::hasColumn('personnel','type')) $q->where('type','=',0);
        });
        if($search) $query->where(function($q) use($search){ $q->where('nom','LIKE','%'.$search.'%')->orWhere('matricule','LIKE','%'.$search.'%'); });
        return $query->orderBy('nom');
    }

    private function years()
    {
        $years=FicheProgression::select('annee_scolaire')->groupBy('annee_scolaire')->orderBy('annee_scolaire','desc')->lists('annee_scolaire');
        if(!is_array($years)) $years=$years->toArray();
        if(!in_array(session('year'),$years)) $years[]=session('year');
        rsort($years);
        return $years;
    }

    private function fichesParCours($annee,$personnelId=null)
    {
        $query=FicheProgression::where('annee_scolaire','=',$annee)->where('id_campus','=',$this->currentCampusId());
        if($personnelId) $query->where('id_personnel','=',$personnelId);
        $rows=array();
        foreach($query->get() as $fiche){
            if(!isset($rows[$fiche->id_cours_enseignant])) $rows[$fiche->id_cours_enseignant]=array();
            $rows[$fiche->id_cours_enseignant][]=$fiche;
        }
        return $rows;
    }

    private function specialiteView(Request $request,$avecFiche)
    {
        $annee=$request->input('annee_scolaire',session('year'));
        $enseignantIds=$this->enseignantsQuery()->lists('id');
        $query=CoursEnseignant::with(array('personnel','cycle','filiere','nniveau','classe','matiere'))->where('annee_scolaire','=',$annee);
        if(count($enseignantIds)) $query->whereIn('id_personnel',$enseignantIds); else $query->whereRaw('1 = 0');
        foreach(array('id_cycle','id_niveau','id_classe','id_nniveau') as $field) if($request->input($field)) $query->where($field,'=',$request->input($field));
        $fiches=$this->fichesParCours($annee);
        $rows=array();
        foreach($query->orderBy('id_classe')->orderBy('id_nniveau')->orderBy('id_personnel')->get() as $cours){
            $has=isset($fiches[$cours->id]) && count($fiches[$cours->id]);
            if(($avecFiche && $has) || (!$avecFiche && !$has)) $rows[]=$cours;
        }
        return view('personnel.fiches_progression.specialites',array(
            'titre'=>$avecFiche?'Cours avec fiches de progression':'Cours sans fiches de progression',
            'rows'=>$rows,'fichesParCours'=>$fiches,'avecFiche'=>$avecFiche,'years'=>$this->years(),'selectedYear'=>$annee,
            'cycles'=>cycle::orderBy('nom')->get(),'niveaux'=>niveau::orderBy('nom')->get(),
            'classes'=>classe::with('niveau')->orderBy('nom')->get(),'nniveaux'=>NNiveau::orderBy('id_cycle')->orderBy('nom')->get()
        ));
    }
}
