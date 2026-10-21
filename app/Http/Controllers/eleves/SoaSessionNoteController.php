<?php namespace gesecole\Http\Controllers\eleves;

use gesecole\Http\Controllers\Controller;
use gesecole\classe;
use gesecole\matiere;
use gesecole\note;
use gesecole\Services\ExamSessionNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SoaSessionNoteController extends Controller
{
    public function __construct() { $this->renewSchoolYear(); }

    public function matiere(Request $request)
    {
        $this->validate($request,array('id'=>'required|integer','cycle'=>'required|integer','nniveau'=>'required|integer'));
        $rows=DB::table('classe_matiere as cm')->join('matiere as m','m.id','=','cm.id_matiere')
            ->leftJoin('session_exams as se',function($join) {
                $join->on('se.id_classe_matiere','=','cm.id')->where('se.annee_academique','=',session('year'));
            })->leftJoin('tutelles_academiques as t','t.id','=','se.id_tutelle')
            ->where('cm.id_classe',$request->input('id'))->where('cm.id_cycle',$request->input('cycle'))->where('cm.id_nniveau',$request->input('nniveau'))
            ->select('m.id as id_matiere','m.nom','m.nom_anglais','cm.semestre','cm.id_code','cm.id as id_classe_matiere',
                'se.id as id_session','se.cc1','se.tpe1','se.sn1','se.ratrappage1','se.cc2','se.tpe2','se.sn2','se.ratrappage2','t.nom as tutelle')
            ->orderBy('m.nom')->orderBy('se.id')->get();
        foreach($rows as $row) $row->phases=ExamSessionNote::phases($row,$row->semestre);
        return view('eleves.matiere_note_soa_sessions',array('titre'=>'Enregistrement des notes de Soa par session',
            'matieres'=>$rows,'classe'=>classe::findOrFail($request->input('id')),'id'=>$request->input('id'),
            'id_cycle'=>$request->input('cycle'),'id_nniveau'=>$request->input('nniveau')));
    }

    private function validateSelection(Request $request)
    {
        $this->validate($request,array('id_session'=>'required|integer','sequence'=>'required|integer|in:1,2,3,4,5,6,7,8',
            'cycle'=>'required|integer','nniveau'=>'required|integer'));
    }

    private function context(Request $request,$classId,$subjectId,$lock=false)
    {
        $query=DB::table('session_exams as se')->join('classe_matiere as cm','cm.id','=','se.id_classe_matiere')
            ->where('se.id',$request->input('id_session'))->where('se.annee_academique',session('year'))
            ->where('cm.id_classe',$classId)->where('cm.id_matiere',$subjectId)
            ->where('cm.id_cycle',$request->input('cycle'))->where('cm.id_nniveau',$request->input('nniveau'))
            ->select('se.*','cm.semestre','cm.id as config_id');
        if($lock) $query->lockForUpdate();
        $config=$query->first();
        if(!$config) abort(422,'La session ne correspond pas à cette matière, cette classe, ce niveau ou cette année.');
        $phases=ExamSessionNote::phases($config,$config->semestre);
        $sequence=(int)$request->input('sequence');
        if(!isset($phases[$sequence]) || $phases[$sequence]->weight>100) abort(422,'Cette épreuve n’est pas activée dans la session sélectionnée.');
        $now=date('Y-m-d H:i:s');
        $locks=DB::table('verrou_note')->where('annee_scolaire',session('year'))->where('sequence',$sequence)
            ->where(function($q){$q->whereNull('target')->orWhere('target','note_sequence');})
            ->where(function($q){$q->whereNull('is_active')->orWhere('is_active',true);})
            ->where(function($q)use($now){$q->whereNull('starts_at')->orWhere('starts_at','<=',$now);})
            ->where(function($q)use($now){$q->whereNull('ends_at')->orWhere('ends_at','>=',$now);})
            ->where(function($q){$q->whereNull('id_campus');if(session('campus_id'))$q->orWhere('id_campus',session('campus_id'));});
        if($locks->exists()) abort(403,trans('admin.mod_locked'));
        return array($config,$phases[$sequence]);
    }

    private function students(Request $request,$classId)
    {
        return DB::table('eleve as e')->join('inscription as i','i.id_eleve','=','e.id')
            ->where('i.annee_scolaire',session('year'))->where('i.id_classe',$classId)
            ->where('i.id_cycle',$request->input('cycle'))->where('i.id_nniveau',$request->input('nniveau'))
            ->select('e.id','e.nom','e.prenom','e.matricule')->distinct()->orderBy('e.nom')->orderBy('e.prenom')->get();
    }

    private function noteQuery(Request $request,$classId,$subjectId,$config,$legacy=false)
    {
        $query=note::where('annee_scolaire',session('year'))->where('id_matiere',$subjectId)->where('sequence',$request->input('sequence'))
            ->where(function($q)use($classId){$q->whereNull('id_classe')->orWhere('id_classe',$classId);})
            ->where(function($q)use($config){$q->whereNull('id_classe_matiere')->orWhere('id_classe_matiere',$config->config_id);});
        if($legacy) $query->whereNull('id_session'); else $query->where('id_session',$config->id);
        return $query;
    }

    private function legacyAllowed(Request $request,$classId,$subjectId,$config)
    {
        return (int) DB::table('session_exams')->where('id_classe_matiere',$config->config_id)->where('annee_academique',session('year'))->count()===1
            && (int) DB::table('classe_matiere')->where('id_classe',$classId)->where('id_matiere',$subjectId)
                ->where('id_cycle',$request->input('cycle'))->where('id_nniveau',$request->input('nniveau'))->count()===1;
    }

    public function create(Request $request,$id)
    {
        $this->validateSelection($request); $this->validate($request,array('id'=>'required|integer'));
        list($config,$phase)=$this->context($request,$id,$request->input('id'));
        $students=$this->students($request,$id); $ids=array();foreach($students as $student)$ids[]=$student->id;
        $notes=$this->noteQuery($request,$id,$request->input('id'),$config)->whereIn('id_eleve',$ids)->get();
        $values=array();foreach($notes as $mark)if(is_numeric($mark->note))$values[$mark->id_eleve]=round($mark->note*20/$phase->weight,4);
        if($this->legacyAllowed($request,$id,$request->input('id'),$config)) {
            $legacy=$this->noteQuery($request,$id,$request->input('id'),$config,true)->whereIn('id_eleve',$ids)->get();
            $grouped=array();foreach($legacy as $mark)$grouped[$mark->id_eleve][]=$mark;
            foreach($grouped as $studentId=>$marks)if(count($marks)===1 && is_numeric($marks[0]->note) && !isset($values[$studentId]))$values[$studentId]=round($marks[0]->note*20/$phase->weight,4);
        }
        return view('eleves.saisie_notes_soa_session',array('titre'=>'Saisie des notes de Soa','eleves'=>$students,'noteValues'=>$values,
            'matiere'=>matiere::findOrFail($request->input('id')),'config'=>$config,'phase'=>$phase,
            'id_classe'=>$id,'id_cycle'=>$request->input('cycle'),'id_nniveau'=>$request->input('nniveau')));
    }

    public function store(Request $request)
    {
        $this->validateSelection($request);
        $this->validate($request,array('id_matiere'=>'required|integer','id_classe'=>'required|integer','eleve'=>'required|array','note'=>'required|array'));
        $classId=(int)$request->input('id_classe'); $subjectId=(int)$request->input('id_matiere');
        DB::transaction(function()use($request,$classId,$subjectId) {
            list($config,$phase)=$this->context($request,$classId,$subjectId,true);
            $allowed=array();foreach($this->students($request,$classId) as $student)$allowed[(string)$student->id]=true;
            $selected=$request->input('eleve'); $rules=array();
            foreach($selected as $studentId) {
                if(!is_scalar($studentId) || !ctype_digit((string)$studentId) || !isset($allowed[(string)$studentId])) abort(422,'Étudiant non inscrit dans cette sélection.');
                $rules['note.'.$studentId]='required|numeric|between:0,20';
            }
            if(count(array_unique($selected))!==count($selected)) abort(422,'Un étudiant est présent plusieurs fois.');
            $this->validate($request,$rules);
            $legacyAllowed=$this->legacyAllowed($request,$classId,$subjectId,$config); $values=$request->input('note');
            foreach($selected as $studentId) {
                $mark=$this->noteQuery($request,$classId,$subjectId,$config)->where('id_eleve',$studentId)->first();
                if(!$mark && $legacyAllowed) {
                    $legacy=$this->noteQuery($request,$classId,$subjectId,$config,true)->where('id_eleve',$studentId)->get();
                    if(count($legacy)===1)$mark=$legacy[0];
                }
                if(!$mark){$mark=new note();$mark->id_creator=\Auth::id();}
                $before=$mark->note;
                $mark->id_matiere=$subjectId; $mark->id_eleve=$studentId; $mark->sequence=$phase->sequence;
                $mark->annee_scolaire=session('year'); $mark->id_session=$config->id;
                $mark->id_classe_matiere=$config->config_id; $mark->id_classe=$classId;
                if(session('campus_id'))$mark->id_campus=session('campus_id');
                $mark->note=ExamSessionNote::weightedMark($values[$studentId],$phase->weight);
                $mark->id_last_editor=\Auth::id();
                $mark->edition_details=trim($mark->edition_details."\n".date('Y-m-d H:i:s').' session '.$config->id.' : '.($before===null?'nouvelle note':$before).' -> '.$mark->note);
                $mark->save();
            }
        });
        return redirect()->to('/eleves/notes/matieres_soa?id='.$classId.'&cycle='.$request->input('cycle').'&nniveau='.$request->input('nniveau'))->with('success','Notes enregistrées pour la session sélectionnée.');
    }
}
