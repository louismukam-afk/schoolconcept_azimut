<?php namespace gesecole\Http\Controllers\eleves;

use gesecole\eleve;
use gesecole\groupeMatieres;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\NNiveau;
use gesecole\Session_exam;
use Illuminate\Support\Facades\Session;
use gesecole\parametres;
use gesecole\classe;
use gesecole\niveau;
use gesecole\cycle;
use Illuminate\Support\Facades\DB;
use gesecole\note;
use gesecole\classeMatiere;
use gesecole\matiere;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Array_;

class relevesController extends Controller {

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
        $values= array('titre'=>trans('admin.releve_notes'));
        $c=classe::orderBy('nom')->get();
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        $cy=cycle::all();
        $n=niveau::all();
        $nn=NNiveau::all();
        $values['niveaux']=$n;
        $values['classes']=$c;
        $values['nniveaux']=$nn;
        $values['cycles']=$cy;

        return view('eleves/classe_releves',$values);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */

    public function create(Request $request)
    {
        $this->validate($request, [
            "cycle" => "required|numeric",
            "nniveau" => "required|numeric",
            "id" => "required|numeric"
        ]);

        $id_classe = $request->input('id');
        $id_cycle = $request->input('cycle');
        $nniveau = $request->input('nniveau');

        $values = ['titre' => trans('admin.releve_notes')];

        // 1. Récupérer les élèves inscrits dans cette classe, cycle et niveau

// 1. Récupérer les élèves inscrits dans cette classe, cycle et niveau
        $eleves = DB::table('eleve as e')
            ->join('inscription as i', 'e.id', '=', 'i.id_eleve')
            ->where('i.annee_scolaire', session('year'))
            ->where('i.id_classe', $id_classe)
            ->where('i.id_cycle', $id_cycle)
            ->where('i.id_nniveau', $nniveau)
            ->orderBy('e.nom')
            ->orderBy('e.prenom')
            ->select('e.*')
            ->get();
        $id_eleves = [];
        foreach ($eleves as $eleve) {
            $id_eleves[] = $eleve->id; // récupère l'ID de l'élève
            // Tu peux maintenant utiliser $id_eleve pour tes requêtes de notes
        }

//        $id_eleves = array_column($eleves, 'id');
// 2. Récupérer les matières de cette classe/niveau/cycle avec session examen configurée
        $matieres = DB::table('classe_matiere as cm')
            ->join('matiere as m', 'cm.id_matiere', '=', 'm.id')
            ->join('session_exams as se', function($join){
                $join->on('cm.id', '=', 'se.id_classe_matiere')
                    ->where('se.annee_academique', '=', session('year'));
            })
            ->where('cm.id_classe', $id_classe)
            ->where('cm.id_cycle', $id_cycle)
            ->where('cm.id_nniveau', $nniveau)
            ->select([
                'm.*',
                'cm.id as id_classe_matiere',
                'cm.coef',
                'cm.semestre',
                'cm.type',
                'cm.id_code',
                'se.id as session_id',
                'se.cc1', 'se.cc2', 'se.sn1', 'se.sn2', 'se.ratrappage1', 'se.ratrappage2'
            ])
            ->get();



//

        // 2. Récupérer les matières avec session d’examen configurée


        // 3. Récupérer toutes les notes de ces élèves pour les séquences CC, SN et Rattrapage
        $sequences = [
            'cc' => [1,2],
            'sn' => [3,4],
            'rattrap' => [5,6]
        ];
        $matiere_ids = [];
        foreach ($matieres as $matiere) {
            $matiere_ids[] = $matiere->id;
        }
        $notes_cc = note::whereIn('id_eleve', $id_eleves)
            ->whereIn('sequence', $sequences['cc'])
            ->whereIn('id_matiere', $matiere_ids)
            ->where('annee_scolaire', session('year'))
            ->get();



        $notes_sn = note::whereIn('id_eleve', $id_eleves)
            ->whereIn('sequence', $sequences['sn'])
            ->whereIn('id_matiere', $matiere_ids)
            ->where('annee_scolaire', session('year'))
            ->get();

        $notes_rattrap = note::whereIn('id_eleve', $id_eleves)
            ->whereIn('sequence', $sequences['rattrap'])
            ->whereIn('id_matiere', $matiere_ids)
            ->where('annee_scolaire', session('year'))
            ->get();

        // 4. Calcul des relevés pour chaque élève
        $proces_v = [];

        foreach($eleves as $eleve) {
            $sNote = 0;
            $eleve_matieres = [];

            foreach($matieres as $matiere) {
                // Récupérer les notes pour chaque séquence
                $note_cc1 = $notes_cc->where('id_eleve', $eleve->id)->where('id_matiere', $matiere->id)->where('sequence', 1)->first();
                $note_cc2 = $notes_cc->where('id_eleve', $eleve->id)->where('id_matiere', $matiere->id)->where('sequence', 2)->first();
                $note_sn1 = $notes_sn->where('id_eleve', $eleve->id)->where('id_matiere', $matiere->id)->where('sequence', 3)->first();
                $note_sn2 = $notes_sn->where('id_eleve', $eleve->id)->where('id_matiere', $matiere->id)->where('sequence', 4)->first();
                $note_r1 = $notes_rattrap->where('id_eleve', $eleve->id)->where('id_matiere', $matiere->id)->where('sequence', 5)->first();
                $note_r2 = $notes_rattrap->where('id_eleve', $eleve->id)->where('id_matiere', $matiere->id)->where('sequence', 6)->first();

                // Calcul des notes semestrielles avec rattrapage
                $note1 = (isset($note_cc1->note) ? $note_cc1->note : 0) + (isset($note_sn1->note) ? $note_sn1->note : 0);
                if($note_r1 && $note1 < 50) {
                    $note1 = (isset($note_cc1->note) ? $note_cc1->note : 0) + $note_r1->note;
                }

                $note2 = (isset($note_cc2->note) ? $note_cc2->note : 0) + (isset($note_sn2->note) ? $note_sn2->note : 0);
                if($note_r2 && $note2 < 50) {
                    $note2 = (isset($note_cc2->note) ? $note_cc2->note : 0) + $note_r2->note;
                }

// Note finale annuelle
                if(is_numeric($note1) && is_numeric($note2)) {
                    $noteFinal = ($note1 + $note2)/2;
                } elseif(is_numeric($note1)) {
                    $noteFinal = $note1;
                } else {
                    $noteFinal = $note2;
                }


                $sNote += $noteFinal * $matiere->coef;

                $eleve_matieres[] = (object)[
                    'id' => $matiere->id,
                    'altName' => $matiere->nom,
                    'note' => $noteFinal,
                    'credit' => $matiere->coef,
                    'semestre' => $matiere->semestre,
                    'type' => $matiere->type,
                    'id_code' => $matiere->id_code
                ];
            }
            // $eleve_matieres contient les objets avec 'note' pour chaque matière
            $total_notes = 0;
            $nb_matieres = count($eleve_matieres);

           /* foreach ($eleve_matieres as $matiere) {
                $total_notes += is_numeric($matiere->note) ? $matiere->note : 0;
            }

            $moyenne_generale = $nb_matieres > 0 ? $total_notes / $nb_matieres : 0;*/

            $sCoefTotal = array_sum(array_column($matieres, 'coef'));

             $moyenne_generale = $sCoefTotal ? $sNote / $sCoefTotal : 0;

            $proces_v[] = (object)[
                'eleve' => $eleve,
                'matieres' => $eleve_matieres,
                'moyenne' => $moyenne_generale
            ];
        }

        // 5. Préparer les valeurs pour la vue
        $classe = Classe::find($id_classe);
        $values['eleves'] = $eleves;
        $values['PV'] = $proces_v;
        $values['matieres'] = $matieres;
        $values['nb_matieres'] = count($matieres);
//        $values['nb_matieres'] = $matieres->count();
        $values['classe'] = $classe;
        $values['cycle'] = Cycle::find($id_cycle);
        $values['nniveau'] = NNiveau::find($nniveau);
        $values['niveau'] = Niveau::find($classe->id_niveau);

        return view('eleves/print_releves', $values);
    }

    public function createko(Request $request)
    {
        // Validation
        $this->validate($request, [
            "cycle" => "required|numeric",
            "nniveau" => "required|numeric",
            "id" => "required|numeric"
        ]);

        $id = $request->input('id');
        $id_cycle = $request->input('cycle');
        $nniveau = $request->input('nniveau');

        // Récupération des élèves inscrits
        $eleves = DB::table('eleve as e')
            ->join('inscription as i', function($join) use ($id, $id_cycle, $nniveau) {
                $join->on('i.id_eleve', '=', 'e.id')
                    ->where('i.annee_scolaire', '=', session('year'))
                    ->where('i.id_classe', '=', $id)
                    ->where('i.id_cycle', '=', $id_cycle)
                    ->where('i.id_nniveau', '=', $nniveau);
            })
            ->orderBy('e.nom')
            ->orderBy('e.prenom')
            ->get();

        $id_eleves = array_column($eleves, 'id');

//        $id_eleves = collect($eleves)->pluck('id')->toArray();

        // Récupération des matières du classe-matière
        $cm = ClasseMatiere::where('id_classe', $id)
            ->where('id_nniveau', $nniveau)
            ->where('id_cycle', $id_cycle)
            ->get();

        $sCoefTotal = $cm->sum('coef');
//        $matiere_list = $cm->pluck('id_matiere')->toArray();
        $matiere_list = [];
        foreach ($cm as $classeMatiere) {
            $matiere_list[] = $classeMatiere->id_matiere;
        }
        $matieres = Matiere::whereIn('matiere.id', $matiere_list)  // <- prefixer ici
        ->join('classe_matiere', 'matiere.id', '=', 'classe_matiere.id_matiere')
            ->where('classe_matiere.id_classe', $id)
            ->where('classe_matiere.id_nniveau', $nniveau)
            ->where('classe_matiere.id_cycle', $id_cycle)
            ->select([
                'matiere.*',
                'classe_matiere.coef',
                'classe_matiere.id AS id_classe_matiere'
            ])
            ->get();


        $proces_v = [];

        foreach($eleves as $eleve){
            $sNote = 0;
            $eleve_matieres = [];

            foreach($matieres as $matiere){
                // Récupération de la session examen pour cet élève et cette matière
                $session = Session_exam::where('id_matiere', $matiere->id)
                    ->where('id_classe_matiere', $matiere->id_classe_matiere)
                    ->where('id_eleve', $eleve->id)
                    ->where('annee_academique', session('year'))
                    ->first();

                // Compatibilité PHP < 7 (pas de ??)
                $cc1 = isset($session->cc1) ? $session->cc1 : 0;
                $sn1 = isset($session->sn1) ? $session->sn1 : 0;
                $cc2 = isset($session->cc2) ? $session->cc2 : 0;
                $sn2 = isset($session->sn2) ? $session->sn2 : 0;
                $rattrap1 = isset($session->ratrappage1) ? $session->ratrappage1 : 0;
                $rattrap2 = isset($session->ratrappage2) ? $session->ratrappage2 : 0;

                $note1 = $cc1 + $sn1;
                $note2 = $cc2 + $sn2;

                if($rattrap1 > 0 && $note1 < 50){
                    $note1 = $cc1 + $rattrap1;
                }
                if($rattrap2 > 0 && $note2 < 50){
                    $note2 = $cc2 + $rattrap2;
                }

                // Calcul de la note finale
                if(is_numeric($note1) && is_numeric($note2)){
                    $noteFinal = ($note1 + $note2) / 2;
                } elseif(!is_numeric($note1)){
                    $noteFinal = $note2;
                } else {
                    $noteFinal = $note1;
                }

                $sNote += $noteFinal * $matiere->coef;

                $eleve_matieres[] = (object)[
                    'id' => $matiere->id,
                    'altName' => $matiere->nom,
                    'note' => $noteFinal,
                    'credit' => $matiere->coef,
                    'semestre' => $matiere->semestre,
                    'type' => $matiere->type,
                    'id_code' => $matiere->id_code
                ];
            }

            $moyenne_generale = $sCoefTotal ? $sNote / $sCoefTotal : 0;

            $proces_v[] = (object)[
                'eleve' => $eleve,
                'matieres' => $eleve_matieres,
                'moyenne' => $moyenne_generale
            ];
        }

        $classe = Classe::find($id);

        return view('eleves.print_releves', [
            'eleves' => $eleves,
            'PV' => $proces_v,
            'matieres' => $matieres,
            'nb_matieres' => count($matieres),
            'classe' => $classe,
            'cycle' => Cycle::find($id_cycle),
            'nniveau' => NNiveau::find($nniveau),
            'niveau' => Niveau::find($classe->id_niveau)
        ]);
    }



    public function create1(Request $request)
	{
	    $id=$request->input('id');
	    $this->validate($request,[
	       "cycle"=>"required|numeric",
            "nniveau"=>"required|numeric",
        ]);
        $proces_v=[];
	    $id_cycle=$request->input('cycle');
	    $nniveau=$request->input('nniveau');
        $values= array('titre'=>trans('admin.releve_notes'));
        $id_eleves=array();
            $semestre=array(1,2);
            $sn=array(3,4);
            $cc=array(1,2);
            $rattrap=array(5,6);
            $sequences_pv=array(1,2,3,4,5,6);


            $e = DB::select('SELECT e.* FROM eleve e,inscription i WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_eleve=e.id AND i.id_cycle=? AND i.id_nniveau=? ORDER BY e.nom,e.prenom', [session('year'), $id, $id_cycle,$nniveau]);
            $j=0;
            $matieres=array();
            foreach ($e as $el){
                $id_eleves[$j]=$el->id;
                $j++;
            }
            $n_rattrap = note::whereAnneeScolaire(session('year'))
                ->whereIn('sequence',$rattrap)
                ->whereIn('id_eleve',$id_eleves)
                ->get();
            $n_sn = note::whereAnneeScolaire(session('year'))
                ->whereIn('sequence',$sn)
                ->whereIn('id_eleve',$id_eleves)
                ->get();
            $n_cc = note::whereAnneeScolaire(session('year'))
                ->whereIn('sequence',$cc)
                ->whereIn('id_eleve',$id_eleves)
                ->get();
            $notes_sequences_pv=note::whereAnneeScolaire(\session('year'))
                ->whereIn('id_eleve',$id_eleves)
                ->whereIn('sequence',$sequences_pv)
                ->get();


            $matieres_pv=$notes_sequences_pv->fetch('id_matiere')->toArray();
            $sCoef=0;
            $cm = classeMatiere::whereIdClasse($id)->where('id_nniveau','=',$nniveau)->where('id_cycle','=',$id_cycle)->get();
            foreach ($cm as $entry){
                $sCoef+=$entry->coef;
            }

            $matiere_list=$cm->fetch('id_matiere')->toArray();
            //$m=matiere::whereIn('id',$matiere_list)->whereIn('id',$matieres_pv)->get();
        $m=matiere::whereIn('matiere.id',$matiere_list)
            ->Join('classe_matiere','matiere.id','=','classe_matiere.id_matiere')
            //->join('codages','codages.id','=','classe_matiere.id_code'   )    
            ->where('id_classe','=',$id)
            ->where('id_cycle','=',$id_cycle)
            ->where('classe_matiere.id_nniveau','=',$nniveau)
            ->select(['matiere.*','id_code','semestre','type','classe_matiere.id AS id_classe_matiere'])
           
            ->get();

            $k=0;
            $gm=groupeMatieres::whereIdClasse($id)->where('id_nniveau','=',$nniveau)->get();
            $m_cps=$gm->fetch('id_matiere_mere')->toArray();

            $calc=$gm->fetch('id_classe_matiere')->toArray();
            $m_cpst= classeMatiere::whereIn('id',$calc)->get()->fetch('id_matiere')->toArray();

            $composes= array();
            $composantes= array();
            $cpstList= array();
            $minus_credit_cpst=false;

            foreach($e as $eleve){

                $l=0;
                $c=0;
                $cst=0;
                $sNote=0;
                foreach ($m as $matiere){
                    $cps=false;
                    $cpst=false;

                    if($this->isIn($matiere->id,$m_cps)){
                        $cps=true;
                        $cpstList=$gm->where('id_matiere_mere',$matiere->id,false)->fetch('id_classe_matiere')->toArray();

                    }
                    $nom=\App::getLocale()=='en'?$matiere->nom_anglais:$matiere->nom;
                    if($this->isIn($matiere->id,$m_cpst))
                        $cpst=true;

                    $credit=$cm->where('id_matiere',$matiere->id)
                        ->first()
                        ->coef;
                    if ($cpst)
                        if(!$minus_credit_cpst) {
                            $sCoef -= $credit;
                        }

                    $note_cc1=$n_cc->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',1,false)
                        ->first();
                    $note_cc2=$n_cc->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',2,false)
                        ->first();
                    $note_sn1=$n_sn->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',3,false)
                        ->first();
                    $note_sn2=$n_sn->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',4,false)
                        ->first();
                    $note_rattrap1=$n_rattrap->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',5,false)
                        ->first();
                    $note_rattrap2=$n_rattrap->where('id_matiere',$matiere->id,false)
                        ->where('id_eleve',$eleve->id,false)
                        ->where('sequence',6,false)
                        ->first();
                    if($note_cc1!=null || $note_sn1!=null ){
                        @$note1=$note_cc1->note+$note_sn1->note;
                        if($note_rattrap1!=null){
                            if($note1>=50){
                                $noteS1=$note1;
                            }else{
                                $noteS1=($note_cc1?$note_cc1->note:0)+$note_rattrap1->note;
                            }
                        }else{
                            $noteS1=$note1;
                        }
                    }else{
                        if($note_rattrap1!=null){

                            $noteS1=$note_rattrap1->note;

                        }else{
                            $noteS1='-';
                        }

                    }
                    if($note_cc2!=null || $note_sn2!=null ){
                        @$note1=$note_cc2->note+$note_sn2->note;
                        if($note_rattrap2!=null){
                            if($note1>=50){
                                $noteS2=$note1;
                            }else{
                                $noteS2=($note_cc2?$note_cc2->note:0)+$note_rattrap2->note;
                            }
                        }else{
                            $noteS2=$note1;
                        }
                    }else{
                        if($note_rattrap2!=null){

                            $noteS2=$note_rattrap2->note;

                        }else{
                            $noteS2='-';
                        }

                    }
                    $note=0;
                    if(is_numeric($noteS1) && is_numeric($noteS2)){
                        $note=($noteS1+$noteS2)/2;
                    }elseif (!is_numeric($noteS2)){
                        $note=$noteS1;
                    }elseif (!is_numeric($noteS1)){
                        $note=$noteS2;
                    }




                    if(is_numeric($note)){
                        if((!$cpst) && (!$cps))
                        $sNote+=($note*$credit);
                    }


                    if((!$cpst) && (!$cps)){
                        $matieres[$l]=new pv1_matiere($matiere->id,$note,$credit,$nom,$matiere->type,$matiere->semestre,$matiere->id_code);
                    }
                    if($cpst){
                       $composantes[$cst]= new composante($matiere->id,$note,$matiere->id_classe_matiere,$nom,$credit);
                       $cst++;
                    }
                    if($cps){
                        $composes[$c]= new compose($matiere->id,$note,$credit,$cpstList,$nom,$credit,$matiere->type,$matiere->semestre,$matiere->id_code);
                        $c++;
                    }

                    $l++;

                }
                $minus_credit_cpst=true;
                foreach($composes as $co){
                    $co_note=0;
                    $co_n=0;
                    $s_co_coef=0;
                    $lnoms='';
                    foreach ($composantes as $compt){
                        if($this->isIn($compt->id_classe_matiere,$co->cpsList)){
                            $co_note+=$compt->note*$compt->credit;
                            $lnoms.=$compt->nom.' ,';
                            $s_co_coef+=$compt->credit;
                            $co_n++;


                        }

                    }


                   /* if($co_n>0){
                        $co_note=$co_note/$co_n;
                    }*/
                    if($s_co_coef>0){
                        $co_note=round($co_note/$s_co_coef,2);
                    }


                  // if(is_numeric($co->note))
                   // $co_note=round(($co_note+$co->note)/2,2);



                    if(sizeof($lnoms)>=1)
                        $lnoms='('.substr($lnoms,0,sizeof($lnoms)-2).')';

                    $sNote+=($co_note*$co->credit);
                    $matieres[$l]=new pv1_matiere($co->id_matiere,$co_note,$s_co_coef,$co->nom.' '.$lnoms,$co->type,$co->semestre,$co->id_code);
                    $l++;

                }


                if($sCoef==0) $sCoef=1;
                $proces_v[$k]=new PV1($eleve->id,$matieres,$sNote/$sCoef);

                $k++;
            }
            $classe=classe::find($id);
            $values['eleves'] = $e;
            $values['PV'] = $proces_v;
            $values['matieres'] = $m;
            $values['nb_matieres'] = $m->count();
            $values['classe'] = $classe;
            $values['cycle']= cycle::find($id_cycle);
            $values['nniveau']=NNiveau::find($nniveau);
            $values['niveau']= niveau::find($classe->id_niveau);
            $values['semestre'] = $semestre;

            return view('eleves/print_releves', $values);


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

	function isIn($val,$array)
{
        $n=sizeof($array);
        for($i=0;$i<$n;$i++){
            if($val==$array[$i])
                return true;
        }
        return false;
}

    function getGrade($note){
        if($note>=80){
            $grade[0]='A';
            $grade[1]=4;
            return $grade;
        }
        if($note>=75){
            $grade[0]='A-';
            $grade[1]=3.7;
            return $grade;
        }
        if($note>=70){
            $grade[0]='B+';
            $grade[1]=3.3;
            return $grade;
        }
        if($note>=65){
            $grade[0]='B';
            $grade[1]=3;
            return $grade;
        }
        if($note>=60){
            $grade[0]='B-';
            $grade[1]=2.7;
            return $grade;
        }
        if($note>=55){
            $grade[0]='C+';
            $grade[1]=2.3;
            return $grade;
        }
        if($note>=50){
            $grade[0]='C';
            $grade[1]=2;
            return $grade;
        }

        if($note>=45){
            $grade[0]='C-';
            $grade[1]=1.7;
            return $grade;
        }
        if($note>=40){
            $grade[0]='D+';
            $grade[1]=1.3;
            return $grade;
        }
        if($note>=35){
            $grade[0]='D';
            $grade[1]=1;
            return $grade;
        }
        if($note>=30){
            $grade[0]='E';
            $grade[1]=0;
            return $grade;
        }
        $grade[0]='F';
        $grade[1]=0;
        return $grade;

    }
}
class PV1{
    public $eleve;
    public $matieres;
    public $mgp;
    public  function __construct($id_e,$ma,$mg)
    {
        $this->eleve=eleve::find($id_e);
        $this->matieres=$ma;
        $this->mgp=$mg;

    }
}
class pv1_matiere{
    public $id_matiere;
    public $note;
    public $credit;
    public $altName;
    public $type;
    public $semestre;
    public $id_code;


    public  function __construct($id_m,$not,$cr,$an,$ty,$se,$co)
    {
        $this->id_matiere=$id_m;
        $this->note=$not;
        $this->credit=$cr;
        $this->altName=$an;
        $this->type=$ty;
        $this->semestre=$se;
        $this->id_code=$co;  


    }

}

class composante{
    public $id_matiere;
    public $note;
    public $id_classe_matiere;
    public $nom;
    public $credit;

    public function __construct($id,$n,$icm,$no,$credit)
    {
        $this->id_matiere=$id;
        $this->note=$n;
        $this->id_classe_matiere=$icm;
        $this->nom=$no;
        $this->credit=$credit;
    }
}
class compose{
    public $id_matiere;
    public $note;
    public $coef;
    public $cpsList;
    public $nom;
    public $credit;
    public $type;
    public $semestre;
       public $id_code;

    public function __construct($id,$n,$c,$cl,$no,$cr,$ty,$se,$co)
    {
        $this->id_matiere=$id;
        $this->note=$n;
        $this->coef=$c;
        $this->cpsList=$cl;
        $this->nom=$no;
        $this->credit=$cr;
        $this->type=$ty;
        $this->semestre=$se;
         $this->id_code=$co;
    }
}
