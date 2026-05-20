<?php namespace gesecole\Http\Controllers\eleves;

use gesecole\bulletin_genere;
use gesecole\classe;
use gesecole\classeMatiere;
use gesecole\cycle;
use gesecole\discipline;
use gesecole\eleve;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\inscription;
use gesecole\matiere;
use gesecole\moyenne_classe;
use gesecole\moyenne_eleve;
use gesecole\moyenne_matiere;
use gesecole\niveau;
use gesecole\NNiveau;
use gesecole\note;
use gesecole\parametres;
use gesecole\Photo;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class bulletinController extends Controller {

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
        $c=classe::with('niveau')->orderBy('id_niveau')
            ->orderBy('nom')
            ->get();
        $cy=cycle::all();
       // $e=eleve::find($id_eleve);
        $nniveau = NNiveau::orderBy('nom')->get();
	    /*$c=classe::all();
        $cy=cycle::all();
        $n=niveau::all();
        $nn=NNiveau::all();*/
       // $values['niveaux']=$n;
        $values['nniveaux']=$nniveau;
        $values['cycles']=$cy;
	    $values['classes']=$c;
        return view('eleves\bull_mode',$values);
	}



    public function index2()
    {
        $values= array('titre'=>trans('eleve.bulletins'));
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        if(session('error')){
            $values['error']=session('error');
            Session::forget('error');
        }

        return view('eleves\search_eleve_bull',$values);
    }

    public function index_tableau(Request $request)
    {
        $values= array('titre'=>trans('admin.tableauH'));

        $id=$request->input('id');
        $values['classe']=$id;
        return view('eleves.sequence_tableau',$values);
    }

    public function create_tableau(Request $request)
    {
        $values= array('titre'=>trans('admin.tableauH'));

        $id=$request->input('id');
        $sequence=$request->input('sequence');
        if($sequence==7)
        {
            $s1=1;
            $s2=2;
        }elseif ($sequence==8)
        {
            $s1=3;
            $s2=4;
        }elseif ($sequence==9)
        {
            $s1=5;
            $s2=6;
        }elseif ($sequence==10)
        {
            $s1=7;
            $s2=8;
            $s3=9;
        }

        $bg=bulletin_genere::whereAnneeScolaire(session('year'))->where('id_classe','=',$id)->where('sequence','=',$sequence)->first();
        if($bg==null){
            if($sequence!=10){
                if(($result=$this->canGenerateBulletinTrim($id,$sequence))===true){
                    $this->generateBulletinTrim($id,$sequence);
                }else{
                    $str='';
                    foreach ($result as $m){
                        $str.='Sequence '.$m.' ,';
                    }
                    $err_msg=trans('admin.cannot_gen_seq').' '.$str;
                    //session(['error'=>$err_msg]);
                    return redirect()->to('/eleves/tableau-honneur/session?id='.$id)->withErrors($err_msg);

                }
            }else{
                if(($result=$this->canGenerateBulletinAnnuel($id,$sequence))===true){
                    $this->generateBulletinAnnuel($id,$sequence);
                }else{
                    $str='';
                    foreach ($result as $m){
                        $str.=trans('eleve.trimestre').' '.$m.' ,';
                    }
                    $err_msg=trans('admin.cannot_generate').' '.$str;
                    //session(['error'=>$err_msg]);
                    return redirect()->to('/eleves/tableau-honneur/session?id='.$id)->withErrors($err_msg);

                }

            }

        }

        $me=moyenne_eleve::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$id)
            ->where('sequence','=',$sequence)
            ->where('moyenne','>=','14')
            ->orderBy('moyenne','desc')
            ->get();
        $list_e=$me->fetch('id_eleve')->toArray();

        $e=eleve::whereIn('id',$list_e)->get();
        $c=classe::find($id);

        if($sequence==10)
        {
            $values['annuel']=true;
        }else
        {
            $values['sequence']=$sequence-6;
        }


        $values['eleves']=$e;
        $values['moyennes']=$me;
        $values['classe']=$c;




        return view('eleves.tableau_honneur',$values);
    }


    public function regenerate(Request $request){
        $this->validate($request,[
           'classe'=>'required|numeric',
            'sequence'=>'required|numeric'
        ]);
        $id=$request->input('classe');
        $id_cycle=$request->input('cycle');
        $id_nniveau=$request->input('nniveau');
        $seq=$request->input('sequence');

        $c=classe::all();
        $cy=cycle::all();
        $nn=NNiveau::all();
        $values['classes']=$c;
        $values['cycles']=$cy;
        $values['nniveaux']=$nn;
        $bg=bulletin_genere::whereAnneeScolaire(\session('year'))
            ->where('id_classe','=',$id)
            ->where('id_cycle','=',$id_cycle)
            ->where('id_nniveau','=',$id_nniveau)
            ->where('sequence','=',$seq)
            ->get();

        foreach ($bg as $bull)
        {
           $bull->delete();
        }
        $values['success']=true;
        return view('eleves\bull_mode',$values);
    }


    public function search(Request $req)
    {
        $pattern=$req->input('search');
            $e=DB::select('SELECT * FROM eleve e WHERE e.id IN (SELECT i.id_eleve FROM inscription i WHERE annee_scolaire=?) AND (lower(e.nom) LIKE lower(?) or lower(e.prenom) LIKE lower(?) or lower(e.matricule) LIKE lower(?)) ORDER BY nom,prenom',[session('year'),'%' . $pattern . '%','%' . $pattern . '%','%' . $pattern . '%']);


            $values = array('titre' => trans('eleve.bulletins'));
            $values['eleves'] = $e;
            session(['search_result' => true]);


        return view('eleves\search_eleve_bull',$values);
    }
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response

     */

    public function print_single_seq(Request $request)
    {
        $this->validate($request, [
            'sequence' => 'required|numeric',
        ]);
        $values = array('titre' => '');

        //Initialisations
        $seq = $request->input('sequence');


        $id_e = $request->input('id');
        $id_c = $request->input('id_c');
        $id_cy = $request->input('id_cy');
        $id_nn = $request->input('id_nn');
        //$id_n=$request->input('id_n');
        $semestre1 = 1;
        $values['sequence'] = $seq;
        $values['id_eleve'] = $id_e;
        $values['id_classe'] = $id_c;
        $values['id_cycle'] = $id_cy;
        $values['id_nniveau'] = $id_nn;
        // $values['id_filiere']=$id_n;

        if ($seq >= 12) {
            return view('eleves\print_single_year', $values);
        }

        if ($seq == 3 || $seq == 4) {
            if ($seq==3)
            {
                $semestre = 1;
                $values['semestre'] = $semestre;
                return view('eleves\print_single_trim', $values);
            }if ($seq==4)
            {
                $semestre = 2;
                $values['semestre'] = $semestre;
                return view('eleves\print_single_trim', $values);
            }
        }
        if ($seq == 1 || $seq == 2) {
            if ($seq == 1) {
                $semestre = 1;
                $values['semestre'] = $semestre;
                return view('eleves\print_single_seq', $values);
            }
            if ($seq == 2){
                $semestre = 2;
            $values['semestre'] = $semestre;
            return view('eleves\print_single_seq', $values);
        }
        }
    }

    public function get_single_seq(Request $request)
    {
        $this->validate($request,[
           'sequence'=>'required|numeric',
        ]);
        $values= array('titre'=>'');

        //Initialisations
        $seq=$request->input('sequence');

        $id_e=$request->input('id');
        $id_c=$request->input('id_c');
        $id_cy=$request->input('id_cy');
        $id_nn=$request->input('id_nn');
       // $id_n=$request->input('id_n');
        //$values['id_cycle']=$id_cy;
        //$values['id_nniveau']=$id_nn;

        $startBull=microtime(true);



        if($id_c==null){
            $c=DB::select('SELECT c.id,c.nom,c.id_niveau, cy.id,cy.nom, nn.id, nn.nom  FROM classe c, cycle cy, n_niveaus nn,inscription i WHERE annee_scolaire=? AND i.id_eleve=? AND i.id_classe=c.id  AND i.id_cycle=cy.id AND i.id_nniveau=nn.id ',[session('year'),$id_e]);
            $e=DB::select('SELECT * FROM eleve e WHERE e.id=?',[$id_e]);
            $cl=$c[0];
        }
        else{
            $c=classe::find($id_c);
            $cys=cycle::find($id_cy);
            $nns=NNiveau::find($id_nn);
            $cl=$c;
            $cys1=$cys;
            $nns1=$nns;

            $e=DB::select('SELECT * FROM eleve e WHERE e.id In(SELECT id_eleve FROM inscription i ,classe c, cycle cy, n_niveaus nn WHERE annee_scolaire=? AND i.id_classe=c.id    AND i.id_cycle=cy.id AND i.id_nniveau=nn.id AND c.id=?  AND cy.id=? AND nn.id=?  ) ORDER BY nom,prenom',[session('year'),$cl->id,$cys1->id, $nns1->id]);
        }
        $e1=DB::select('SELECT * FROM eleve e WHERE e.id In(SELECT id_eleve FROM inscription i ,classe c, cycle cy, n_niveaus nn WHERE annee_scolaire=? AND i.id_classe=c.id    AND i.id_cycle=cy.id AND i.id_nniveau=nn.id AND c.id=?  AND cy.id=? AND nn.id=?  ) ORDER BY nom,prenom',[session('year'),$cl->id,$cys1->id, $nns1->id]);

       // $e1=DB::select('SELECT * FROM eleve e WHERE e.id In(SELECT id_eleve FROM inscription i ,classe c WHERE annee_scolaire=? AND i.id_classe=c.id AND c.id=?   ) ORDER BY nom,prenom',[session('year'),$cl->id]);

        $list_e=[];
        $i=0;
        foreach ($e1 as $eleve){
            $list_e[$i]=$eleve->id;
            $i++;
        }



        /*try {
            // Code susceptible de générer une erreur
            $bg = bulletin_genere::whereAnneeScolaire(session('year'))
                ->where('id_classe', '=', $cl->id)
                ->where('id_cycle', '=', $cys1->id)
                ->where('id_nniveau', '=', $nns1->id)
                ->where('sequence', '=', $seq)
                ->first();
        } catch (\InvalidArgumentException $e) {
            // Log l'exception et/ou la retourner dans une réponse plus détaillée
            Log::error('Invalid argument exception: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid argument: ' . $e->getMessage()], 400);
        }*/

        $bg = bulletin_genere::whereAnneeScolaire(session('year'))
            ->where('id_classe', '=', $cl->id)
            ->where('id_cycle', '=', $cys1->id)
            ->where('id_nniveau', '=', $nns1->id)
            ->where('sequence', '=', $seq)
            ->first();

       $startGen = microtime(true);
        if ($bg == null) {
            $result = $this->canGenerateBulletin($cl->id, $cys1->id, $nns1->id, $seq);

            if (!is_array($result) || !isset($result['status'])) {
                return response()->json(['error' => trans('eleve.unexpected_error')], 500);
            }

            if ($result['status'] === 'success') {
                // Générer les bulletins uniquement avec les matières ayant des notes
                $this->generateBulletin($cl->id, $cys1->id, $nns1->id, $seq, $result['matieresAvecNotes']);
            } elseif ($result['status'] === 'error' && isset($result['matieresSansNotes'])) {
                // Traiter les matières sans notes
                $str = '';
                foreach ($result['matieresSansNotes'] as $matiere) {
                    $str .= ((\session('locale') == 'en') ? $matiere->nom_anglais : $matiere->nom) . ', ';
                }
                $err_msg = trans('eleve.cannot_generate') . ' ' . rtrim($str, ', ');
                return response()->json(['error' => $err_msg], 400);
            } else {
                // Gestion des erreurs inattendues
                return response()->json(['error' => trans('eleve.unexpected_error')], 500);
            }
        }

    /*   if ($bg == null) {
            $result = $this->canGenerateBulletin($cl->id, $cys1->id, $nns1->id, $seq);

            if ($result['status'] === 'success') {
                // Générer les bulletins uniquement avec les matières ayant des notes
                $this->generateBulletin($cl->id, $cys1->id, $nns1->id, $seq, $result['matieresAvecNotes']);
            } elseif ($result['status'] === 'error' && isset($result['matieresSansNotes'])) {
                // Traiter les matières sans notes
                $str = '';
                foreach ($result['matieresSansNotes'] as $matiere) {
                    $str .= ((\session('locale') == 'en') ? $matiere->nom_anglais : $matiere->nom) . ', ';
                }
                $err_msg = trans('eleve.cannot_generate') . ' ' . rtrim($str, ', ');
                return response()->json(['error' => $err_msg], 400);
            } else {
                // Gestion des erreurs inattendues
                return response()->json(['error' => trans('eleve.unexpected_error')], 500);
            }
        }*/
       /* if($bg==null){
            if(($result=$this->canGenerateBulletin($cl->id,$cys1->id, $nns1->id,$seq))===true){
                $this->generateBulletin($cl->id,$cys1->id, $nns1->id,$seq);
            }else{
                $str='';
                foreach ($result as $m){
                    $str.=((\session('locale')=='en')?$m->nom_anglais:$m->nom). ' ,';
                }
                $err_msg=trans('eleve.cannot_generate').' '.$str;
                //session(['error'=>$err_msg]);
                return response()->json(['error'=>$err_msg],500);
                // return redirect()->action('eleves\bulletinController@index2');

            }
        }*/

        $endGen=microtime(true);

        $notes=note::whereAnneeScolaire(session('year'))
            ->where('sequence','=',$seq)->orderBy('note','desc')
            ->whereIn('id_eleve',$list_e)
            ->get();
        $d=discipline::whereAnneeScolaire(session('year'))
            ->whereIn('id_eleve',$list_e)
            ->where('sequence','=',$seq)
            ->get();
        $ii=0;
        $discList=array();
        foreach ($list_e as $el1)
        {
            $abscence_inj=$d->where('type',0,false)
                ->where('id_eleve',$el1,false)
                ->where('just','0',false)
                ->sum('nb_heures');
            $abscence_just=$d->where('type',0,false)
                ->where('id_eleve',$el1,false)
                ->where('just','1',false)
                ->sum('nb_heures');
            $retard=$d->where('type',1,false)
                ->where('id_eleve',$el1,false)
                ->count();
            $avertissement=$d->where('type',3,false)
                ->where('id_eleve',$el1,false)
                ->count();
            $blame=$d->where('type',4,false)
                ->where('id_eleve',$el1,false)
                ->count();
            $exclusion=$d->where('type',2,false)
                ->where('id_eleve',$el1,false)
                ->sum('nb_jours');


            $discList[$ii]=new disc($el1,$avertissement,$exclusion,$blame,$retard,$abscence_inj,$abscence_just);
            $ii++;
        }


        $eff=inscription::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$cl->id)
            ->where('id_cycle','=',$cys1->id)
            ->where('id_nniveau','=',$nns1->id)
            ->count();


        //2emes initialisations
        $mm=moyenne_matiere::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$cl->id)
            ->where('id_cycle','=',$cys1->id)
            ->where('id_nniveau','=',$nns1->id)
            ->where('sequence','=',$seq)
            ->get();
        $me=moyenne_eleve::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$cl->id)
            ->where('id_cycle','=',$cys1->id)
            ->where('id_nniveau','=',$nns1->id)
            ->where('sequence','=',$seq)
            ->orderBy('moyenne','desc')
            ->get();
        $mc=moyenne_classe::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$cl->id)
            ->where('id_cycle','=',$cys1->id)
            ->where('id_nniveau','=',$nns1->id)
            ->where('sequence','=',$seq)
            ->get()->first();





        $p=parametres::all();
        $nom_e=$p->where('nom','nom_etablissement',false)->first()->valeur;
        $adresse=$p->where('nom','adresse',false)->first()->valeur;
        $web=$p->where('nom','web',false)->first()->valeur;
        $email=$p->where('nom','email',false)->first()->valeur;
        $numero=$p->where('nom','telephone',false)->first()->valeur;




       /* $m=matiere::whereIn('matiere.id',$matiere_list)
            ->Join('classe_matiere','matiere.id','=','classe_matiere.id_matiere')
            //->join('note','matiere.id','=','note.id_matiere' AND 'note.sequence','=',$seq)
          //  ->join('note','matiere.id','=','note.id_matiere' AND 'note.sequence','=',$seq)
            // ->join('codages','codages.id','=','classe_matiere.id_code'   )
            ->where('id_classe','=',$cl->id)
            ->where('id_cycle','=',$cys1->id)
            ->where('classe_matiere.id_nniveau','=',$nns1->id)
            ->select(['matiere.*','id_code','semestre','type','coef','classe_matiere.id AS id_classe_matiere'])

            ->get();*/
        $cm = classeMatiere::where('id_classe',$cl->id)
            ->where('id_nniveau','=',$nns1->id)
            ->where('id_cycle','=',$cys1->id)
            ->get();
        // Extraire les ID des matières depuis $cm
        $matiere_list = $cm->fetch('id_matiere')->toArray(); // Utilisez pluck() au lieu de fetch()

        $m = matiere::whereIn('matiere.id', $matiere_list) // Filtrer sur les matières récupérées
        ->join('classe_matiere', 'matiere.id', '=', 'classe_matiere.id_matiere') // Lien avec classe_matiere
        ->join('note', function ($join) use ($seq) {
            $join->on('matiere.id', '=', 'note.id_matiere')
                ->where('note.sequence', '=', $seq) // Filtrer sur la séquence
                ->where('note.annee_scolaire', '=', session('year')); // Filtrer sur l'année académique
        })
            ->join('inscription', function ($join) use ($id_c) {
                $join->on('inscription.id_eleve', '=', 'note.id_eleve') // Lien entre inscription et note
                ->where('inscription.id_classe', '=', $id_c) // Filtre sur la classe
                ->where('inscription.annee_scolaire', '=', session('year')); // Filtrer sur l'année académique
            })
            ->where('classe_matiere.id_classe', '=', $cl->id) // Utiliser l'origine exacte pour id_classe
            ->where('classe_matiere.id_cycle', '=', $cys1->id) // Cycle
            ->where('classe_matiere.id_nniveau', '=', $nns1->id) // Niveau
            ->select([
                'matiere.id', // ID unique de la matière
                'matiere.nom', // Nom de la matière (ou tout autre champ pertinent)
                'classe_matiere.id AS id_classe_matiere', // ID spécifique de la table classe_matiere
                'classe_matiere.id_code', // Autres champs nécessaires
                'classe_matiere.semestre',
                'classe_matiere.type',
                'classe_matiere.coef'
            ])
            ->distinct() // Supprime les doublons en termes de matières
            ->get();

        //dump($m);
          //   die();





        /*$m=DB::select('SELECT m.*,cm.coef,cm.groupe,cm.max,p.nom as personnel  cy.nom, nn.nom
                    FROM matiere m,classe_matiere cm,cycle cy,n_niveaus nn WHERE cm.id_classe=?  AND cm.id_cycle= ? AND cm.id_nniveau= ? AND cm.id_matiere=m.id AND 
                    UNION SELECT m.*,cm.coef,cm.groupe,ag.ordre,g.nom as nom_groupe,g.nom_anglais as nom_groupe_dl,cm.max,\'\' as personnel
                     FROM matiere m,classe_matiere cm,WHERE cm.id_classe=? AND cm.id_matiere=m.id 
                      ORDER BY ordre ,nom',[$cl->id,$nns1->id,$cys1->id,\session('year'),$cl->id]);*/



        $values['titre_matiere']=trans('admin.matiere');

        $values['etablissement']=$nom_e;
        $values['adresse']=$adresse;
        $values['web']=$web;
        $values['notes']=$notes;
        $values['email']=$email;
        $values['numero']=$numero;
        $values['sequence']=$seq;
        $values['classe']=$cl;
        $values['cycle']=$cys1;
        $values['niveau']=$nns1;
        $values['eleves']=$e;
        $values['disciplines']=$discList;
        $values['moyenne_matiere']=$mm;
        $values['moyenne_eleve']=$me;
        $values['moyenne_classe']=$mc;
        $values['matieres']=$m;
        $values['effectif']=$eff;
        $values['local']=App::getLocale();

        $photo=Photo::whereAnneeScolaire(\session('year'))
            ->whereIn('eleve_id',$list_e)
            ->get();

        $values['photos']=$photo;

        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        $values['param']=$p;
        $endBull=microtime(true);


      /*  echo 'Bull time= '.($endBull-$startBull);
        echo '<br>';
        echo 'Gen time= '.($endGen-$startGen);
        die();*/
      //dump($eff);
      //die;
  return response()->json($values);

        //return response()->json($values);
    }

    public function print_single_trim(Request $request)
    {
        $this->validate($request,[
            'sequence'=>'required|numeric',
        ]);
        $values= array('titre'=>'');
        $startBull=microtime(true);

        //Initialisations
        $seq=$request->input('sequence');
        $id_e=$request->input('id');
        $id_c=$request->input('id_c');


        if($seq==9) {
            $seq1 = 1;
            $seq2 = 2;
            $seq3 = 3;
        }
        elseif ($seq==10){
            $seq1 = 4;
            $seq2 = 5;
            $seq3 = 6;
        }
        elseif ($seq==11){
            $seq1 = 7;
            $seq2 = 8;
            $seq3 = '';
        }

        if($id_c==null){
            $c=DB::select('SELECT c.id,c.nom,c.id_niveau FROM classe c,inscription i WHERE annee_scolaire=? AND i.id_eleve=? AND i.id_classe=c.id ',[session('year'),$id_e]);
            $e=DB::select('SELECT * FROM eleve e WHERE e.id=?',[$id_e]);
            $cl=$c[0];
        }
        else{
            $c=classe::find($id_c);
            $cl=$c;
            $e=DB::select('SELECT * FROM eleve e WHERE e.id In(SELECT id_eleve FROM inscription i ,classe c, cycle cy, n_niveaus nn WHERE annee_scolaire=? AND i.id_classe=c.id AND c.id=?  AND i.id_cycle=cy.id AND i.id_nniveau=nn.id AND cy.id=? AND nn.id=?  ) ORDER BY nom,prenom',[session('year'),$cl->id]);
        }
        $e1=DB::select('SELECT * FROM eleve e WHERE e.id In(SELECT id_eleve FROM inscription i ,classe c, cycle cy, n_niveaus nn  WHERE annee_scolaire=? AND i.id_classe=c.id AND c.id=? AND i.id_cycle=cy.id AND i.id_nniveau=nn.id AND cy.id=? AND nn.id=?  ) ORDER BY nom,prenom',[session('year'),$cl->id]);

        $list_e=[];
        $i=0;
        foreach ($e1 as $eleve){
            $list_e[$i]=$eleve->id;
            $i++;
        }

        //Vérifications
        $bg=bulletin_genere::whereAnneeScolaire(session('year'))->where('id_classe','=',$cl->id)->where('sequence','=',$seq)->first();
        $startGen=microtime(true);

        if($bg==null){
            if(($result=$this->canGenerateBulletinTrim($cl->id,$seq))===true){
                $this->generateBulletinTrim($cl->id,$seq);
            }else{
                $str='';
                foreach ($result as $m){
                    $str.='Sequence '.$m.' ,';
                }
                $err_msg=trans('admin.cannot_gen_seq').' '.$str;
                //session(['error'=>$err_msg]);
                //return redirect()->action('eleves\bulletinController@index2');
                return response()->json(['error'=>$err_msg],500);

            }
        }
        $endGen=microtime(true);


           if ($seq3!=null){
               $notes=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();
               $notesSeq1=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq1)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();
               $notesSeq2=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq2)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();
               $notesSeq3=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq3)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();

           }else{
               $notes=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();
               $notesSeq1=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq1)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();
               $notesSeq2=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq2)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();
               $notesSeq3=null;
           }

        $d=discipline::whereAnneeScolaire(session('year'))
            ->whereIn('id_eleve',$list_e)
            ->whereRaw('(sequence=? OR sequence=?)',[$seq1,$seq2])
            ->get();


        $ii=0;
        $discList=array();
        foreach ($list_e as $el1)
        {
            $abscence_inj=$d->where('type',0,false)
                ->where('id_eleve',$el1,false)
                ->where('just','0',false)
                ->sum('nb_heures');
            $abscence_just=$d->where('type',0,false)
                ->where('id_eleve',$el1,false)
                ->where('just','1',false)
                ->sum('nb_heures');
            $retard=$d->where('type',1,false)
                ->where('id_eleve',$el1,false)
                ->count();
            $avertissement=$d->where('type',3,false)
                ->where('id_eleve',$el1,false)
                ->count();
            $blame=$d->where('type',4,false)
                ->where('id_eleve',$el1,false)
                ->count();
            $exclusion=$d->where('type',2,false)
                ->where('id_eleve',$el1,false)
                ->sum('nb_jours');


            $discList[$ii]=new disc($el1,$avertissement,$exclusion,$blame,$retard,$abscence_inj,$abscence_just);
            $ii++;
        }

        $eff=inscription::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$cl->id)
            ->count();


        //2emes initialisations
        $mm=moyenne_matiere::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$cl->id)
            ->where('sequence','=',$seq)
            ->get();
        $me=moyenne_eleve::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$cl ->id)
            ->where('sequence','=',$seq)
            ->orderBy('moyenne','desc')
            ->get();
        $mc=moyenne_classe::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$cl->id)
            ->where('sequence','=',$seq)
            ->get()->first();





        $p=parametres::all();
        $nom_e=$p->where('nom','nom_etablissement',false)->first()->valeur;
        $adresse=$p->where('nom','adresse',false)->first()->valeur;
        $web=$p->where('nom','web',false)->first()->valeur;
        $email=$p->where('nom','email',false)->first()->valeur;
        $numero=$p->where('nom','telephone',false)->first()->valeur;
       // $m=DB::select('SELECT m.*,cm.coef,cm.groupe,ag.ordre,g.nom as nom_groupe,g.nom_anglais as nom_groupe_dl,cm.max,p.nom as personnel FROM matiere m,classe_matiere cm,groupes g,affect_groupes ag,(SELECT DISTINCT id_personnel,id_matiere,id_classe,annee_scolaire FROM dispense ) d,personnel p WHERE cm.id_classe=? AND cm.id_matiere=m.id AND  ag.id_groupe=cm.groupe AND ag.id_classe=cm.id_classe AND cm.groupe=g.id AND ( cm.id_classe=d.id_classe AND m.id=d.id_matiere AND d.id_personnel=p.id AND d.annee_scolaire=? ) UNION SELECT m.*,cm.coef,cm.groupe,ag.ordre,g.nom as nom_groupe,g.nom_anglais as nom_groupe_dl,cm.max,\'\' as personnel FROM matiere m,classe_matiere cm,groupes g,affect_groupes ag WHERE cm.id_classe=? AND cm.id_matiere=m.id AND ag.id_groupe=cm.groupe AND ag.id_classe=cm.id_classe AND cm.groupe=g.id AND NOT EXISTS (SELECT * FROM dispense d2 WHERE d2.id_matiere=cm.id_matiere AND d2.id_classe=cm.id_classe ) ORDER BY ordre ,nom',[$cl->id,\session('year'),$cl->id]);

        $cm = classeMatiere::whereIdClasse($cl->id)->where('id_nniveau','=',$nns1->id)->where('id_cycle','=',$cys1->id)->get();
        $matiere_list=$cm->fetch('id_matiere')->toArray();

        $m=matiere::whereIn('matiere.id',$matiere_list)
            ->Join('classe_matiere','matiere.id','=','classe_matiere.id_matiere')
            // ->join('codages','codages.id','=','classe_matiere.id_code'   )
            ->where('id_classe','=',$cl->id)
            ->where('id_cycle','=',$cys1->id)
            ->where('classe_matiere.id_nniveau','=',$nns1->id)
            ->select(['matiere.*','id_code','semestre','type','coef','classe_matiere.id AS id_classe_matiere'])

            ->get();

        $values['titre_matiere']=trans('admin.matiere');
        $values['etablissement']=$nom_e;
        $values['adresse']=$adresse;
        $values['web']=$web;

        $values['notes']=$notes;
        $values['seq1']=$seq1;
        $values['seq2']=$seq2;
        $values['seq3']=$seq3;
        $values['notesSeq1']=$notesSeq1;
        $values['notesSeq2']=$notesSeq2;
        $values['notesSeq3']=$notesSeq3;

        $values['email']=$email;
        $values['numero']=$numero;
        $values['sequence']=$seq;
        $values['classe']=$cl;
        $values['disciplines']=$discList;
        $values['moyenne_matiere']=$mm;
        $values['moyenne_eleve']=$me;
        $values['moyenne_classe']=$mc;
        $values['eleves']=$e;
        $values['matieres']=$m;
        $values['effectif']=$eff;
        $values['local']=App::getLocale();


        $photo=Photo::whereAnneeScolaire(\session('year'))
            ->whereIn('eleve_id',$list_e)
            ->get();

        $values['photos']=$photo;

        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        $values['param']=$p;
        $endBull=microtime(true);

       /*   echo 'Bull time= '.($endBull-$startBull);
      echo '<br>';
      echo 'Gen time= '.($endGen-$startGen);
      die();*/


        return response()->json($values);
    }

    public function print_single_year(Request $request)
    {
        $this->validate($request,[
            'sequence'=>'required|numeric',
        ]);
        $values= array('titre'=>'');

        //Initialisations
        $seq=$request->input('sequence');
        $id_e=$request->input('id');
        $id_c=$request->input('id_c');

        $startBull=microtime(true);


        if($seq==12) {
            $seq1 = 9;
            $seq2 = 10;
            $seq3 = 11;
        }


        if($id_c==null){
            $c=DB::select('SELECT c.id,c.nom,c.id_niveau FROM classe c,inscription i WHERE annee_scolaire=? AND i.id_eleve=? AND i.id_classe=c.id ',[session('year'),$id_e]);
            $e=DB::select('SELECT * FROM eleve e WHERE e.id=?',[$id_e]);
            $cl=$c[0];
        }
        else{
            $c=classe::find($id_c);
            $cl=$c;
            $e=DB::select('SELECT * FROM eleve e WHERE e.id In(SELECT id_eleve FROM inscription i ,classe c WHERE annee_scolaire=? AND i.id_classe=c.id AND c.id=?   ) ORDER BY nom,prenom',[session('year'),$cl->id]);
        }
        $e1=DB::select('SELECT * FROM eleve e WHERE e.id In(SELECT id_eleve FROM inscription i ,classe c WHERE annee_scolaire=? AND i.id_classe=c.id AND c.id=?   ) ORDER BY nom,prenom',[session('year'),$cl->id]);

        $list_e=[];
        $i=0;
        foreach ($e1 as $eleve){
            $list_e[$i]=$eleve->id;
            $i++;
        }

        $bg=bulletin_genere::whereAnneeScolaire(session('year'))->where('id_classe','=',$cl->id)->where('sequence','=',$seq)->first();

        $startGen=microtime(true);

        if($bg==null){
            if(($result=$this->canGenerateBulletinAnnuel($cl->id,$seq))===true){
                $this->generateBulletinAnnuel($cl->id,$seq);
            }else{
                $str='';
                foreach ($result as $m){
                    $str.=trans('eleve.trimestre').' '.$m.' ,';
                }
                $err_msg=trans('admin.cannot_generate').' '.$str;
                //session(['error'=>$err_msg]);
                //return redirect()->action('eleves\bulletinController@index2');
                return response()->json(['error'=>$err_msg],500);

            }
        }
        $endGen=microtime(true);


        $notes=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();
        $notesSeq1=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq1)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();
        $notesSeq2=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq2)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();
        $notesSeq3=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq3)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();



        $d=discipline::whereAnneeScolaire(session('year'))
            ->whereIn('id_eleve',$list_e)
            ->get();

        $ii=0;
        $discList=array();

        foreach ($list_e as $el1)
        {

            $abscence_inj=$d->where('type',0,false)
                ->where('id_eleve',$el1,false)
                ->where('just','0',false)
                ->sum('nb_heures');
            $abscence_just=$d->where('type',0,false)
                ->where('id_eleve',$el1,false)
                ->where('just','1',false)
                ->sum('nb_heures');
            $retard=$d->where('type',1,false)
                ->where('id_eleve',$el1,false)
                ->count();
            $avertissement=$d->where('type',3,false)
                ->where('id_eleve',$el1,false)
                ->count();
            $blame=$d->where('type',4,false)
                ->where('id_eleve',$el1,false)
                ->count();
            $exclusion=$d->where('type',2,false)
                ->where('id_eleve',$el1,false)
                ->sum('nb_jours');


            $discList[$ii]=new disc($el1,$avertissement,$exclusion,$blame,$retard,$abscence_inj,$abscence_just);
            $ii++;
        }


        $eff=inscription::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$cl->id)
            ->count();


        //Vérifications

        //2emes initialisations
        $mm=moyenne_matiere::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$cl->id)
            ->where('sequence','=',$seq)
            ->get();
        $me=moyenne_eleve::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$cl ->id)
            ->where('sequence','=',$seq)
            ->orderBy('moyenne','desc')
            ->get();
        $mc=moyenne_classe::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$cl->id)
            ->where('sequence','=',$seq)
            ->get()->first();






        $p=parametres::all();
        $nom_e=$p->where('nom','nom_etablissement',false)->first()->valeur;
        $adresse=$p->where('nom','adresse',false)->first()->valeur;
        $web=$p->where('nom','web',false)->first()->valeur;
        $email=$p->where('nom','email',false)->first()->valeur;
        $numero=$p->where('nom','telephone',false)->first()->valeur;
       // $m=DB::select('SELECT m.*,cm.coef,cm.groupe,ag.ordre,g.nom as nom_groupe,g.nom_anglais as nom_groupe_dl,cm.max,p.nom as personnel FROM matiere m,classe_matiere cm,groupes g,affect_groupes ag,(SELECT DISTINCT id_personnel,id_matiere,id_classe,annee_scolaire FROM dispense ) d,personnel p WHERE cm.id_classe=? AND cm.id_matiere=m.id AND  ag.id_groupe=cm.groupe AND ag.id_classe=cm.id_classe AND cm.groupe=g.id AND ( cm.id_classe=d.id_classe AND m.id=d.id_matiere AND d.id_personnel=p.id AND d.annee_scolaire=? ) UNION SELECT m.*,cm.coef,cm.groupe,ag.ordre,g.nom as nom_groupe,g.nom_anglais as nom_groupe_dl,cm.max,\'\' as personnel FROM matiere m,classe_matiere cm,groupes g,affect_groupes ag WHERE cm.id_classe=? AND cm.id_matiere=m.id AND ag.id_groupe=cm.groupe AND ag.id_classe=cm.id_classe AND cm.groupe=g.id AND NOT EXISTS (SELECT * FROM dispense d2 WHERE d2.id_matiere=cm.id_matiere AND d2.id_classe=cm.id_classe ) ORDER BY ordre ,nom',[$cl->id,\session('year'),$cl->id]);
        $cm = classeMatiere::whereIdClasse($cl->id)->where('id_nniveau','=',$nns1->id)->where('id_cycle','=',$cys1->id)->get();
        $matiere_list=$cm->fetch('id_matiere')->toArray();

        $m=matiere::whereIn('matiere.id',$matiere_list)
            ->Join('classe_matiere','matiere.id','=','classe_matiere.id_matiere')
            // ->join('codages','codages.id','=','classe_matiere.id_code'   )
            ->where('id_classe','=',$cl->id)
            ->where('id_cycle','=',$cys1->id)
            ->where('classe_matiere.id_nniveau','=',$nns1->id)
            ->select(['matiere.*','id_code','semestre','type','coef','classe_matiere.id AS id_classe_matiere'])

            ->get();

        $values['titre_matiere']=trans('admin.matiere');

        $values['etablissement']=$nom_e;
        $values['adresse']=$adresse;
        $values['web']=$web;

        $values['notes']=$notes;
        $values['seq1']=$seq1;
        $values['seq2']=$seq2;
        $values['seq3']=$seq3;
        $values['notesSeq1']=$notesSeq1;
        $values['notesSeq2']=$notesSeq2;
        $values['notesSeq3']=$notesSeq3;

        $values['email']=$email;
        $values['numero']=$numero;
        $values['sequence']=$seq;
        $values['classe']=$cl;
        $values['disciplines']=$discList;


        $values['moyenne_matiere']=$mm;
        $values['moyenne_eleve']=$me;
        $values['moyenne_classe']=$mc;



        $values['eleves']=$e;
        $values['matieres']=$m;
        $values['effectif']=$eff;
        $values['local']=App::getLocale();


        $photo=Photo::whereAnneeScolaire(\session('year'))
            ->whereIn('eleve_id',$list_e)
            ->get();

        $values['photos']=$photo;

        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        $values['param']=$p;
        $endBull=microtime(true);

      /*     echo 'Bull time= '.($endBull-$startBull);
    echo '<br>';
    echo 'Gen time= '.($endGen-$startGen);
    die();*/

        return response()->json($values);
    }

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
   /* public function canGenerateBulletin($id_classe, $id_cycle, $id_nniveau, $sequence) {
        // Récupérer les matières sans notes
        $matieresSansNotes = DB::table('classe_matiere as cm')
            ->where('cm.id_classe', $id_classe)
            ->where('cm.id_cycle', $id_cycle)
            ->where('cm.id_nniveau', $id_nniveau)
            ->where('cm.type', '>=', 0)
            ->whereNotExists(function ($query) use ($sequence) {
                $query->select(DB::raw(1))
                    ->from('note as n')
                    ->whereRaw('n.id_matiere = cm.id_matiere')
                    ->where('n.annee_scolaire', session('year'))
                    ->where('n.sequence', $sequence);
            })
            ->pluck('id_matiere'); // Récupère les IDs des matières sans notes

        // Vérification pour s'assurer que c'est une collection ou un tableau
        if (!is_array($matieresSansNotes) && !$matieresSansNotes instanceof \Illuminate\Support\Collection) {
            $matieresSansNotes = collect($matieresSansNotes);
        }

        // Récupérer toutes les matières
        $toutesMatieres = DB::table('classe_matiere')
            ->where('id_classe', $id_classe)
            ->where('id_cycle', $id_cycle)
            ->where('id_nniveau', $id_nniveau)
            ->where('type', '>=', 0)
            ->pluck('id_matiere'); // Récupère les IDs des matières

        // Vérification pour s'assurer que c'est une collection ou un tableau
        if (!is_array($toutesMatieres) && !$toutesMatieres instanceof \Illuminate\Support\Collection) {
            $toutesMatieres = collect($toutesMatieres);
        }

        // Vérifier si $toutesMatieres est vide
        if ($toutesMatieres->isEmpty()) {
            return [
                'status' => 'error',
                'message' => trans('eleve.cannot_generate_no_subjects'), // Aucun sujet trouvé
            ];
        }

        // Calculer les matières avec notes
        $matieresAvecNotes = $toutesMatieres->diff($matieresSansNotes);

        return [
            'status' => 'success',
            'matieresAvecNotes' => $matieresAvecNotes->toArray(), // Matières avec notes
            'matieresSansNotes' => $matieresSansNotes->toArray(), // Matières sans notes
        ];
    }
    public function canGenerateBulletin($id_classe, $id_cycle, $id_nniveau, $sequence) {
        // Récupérer les matières sans notes via une jointure
        $matieresSansNotes = DB::table('classe_matiere as cm')
            ->leftJoin('note as n', function ($join) use ($sequence) {
                $join->on('cm.id_matiere', '=', 'n.id_matiere')
                    ->where('n.annee_scolaire', '=', session('year')) // Ajout explicite de l'opérateur '='
                    ->where('n.sequence', '=', $sequence);            // Ajout explicite de l'opérateur '='
            })
            ->whereNull('n.id') // Filtrer les matières sans notes
            ->where('cm.id_classe', '=', $id_classe)
            ->where('cm.id_cycle', '=', $id_cycle)
            ->where('cm.id_nniveau', '=', $id_nniveau)
            ->where('cm.type', '>=', 0)
            ->get();

        // Récupérer toutes les matières
        $toutesMatieres = DB::table('classe_matiere')
            ->where('id_classe', '=', $id_classe)
            ->where('id_cycle', '=', $id_cycle)
            ->where('id_nniveau', '=', $id_nniveau)
            ->where('type', '>=', 0)
            ->get();

        // Convertir les résultats en collections Laravel pour pouvoir utiliser diff()
        $matieresSansNotesCollection = collect($matieresSansNotes);
        $toutesMatieresCollection = collect($toutesMatieres);

        // Vérifier si la liste de toutes les matières est vide en utilisant count() au lieu de isEmpty()
        if ($toutesMatieresCollection->isEmpty()) {
            return [
                'status' => 'error',
                'message' => trans('eleve.cannot_generate_no_subjects'), // Aucun sujet trouvé
            ];
        }

        // Calculer les matières avec notes en comparant les collections
        $matieresAvecNotes = $toutesMatieresCollection->diff($matieresSansNotesCollection);

        return [
            'status' => 'success',
            'matieresAvecNotes' => $matieresAvecNotes, // Matières avec notes
            'matieresSansNotes' => $matieresSansNotes, // Matières sans notes
        ];
    }


    public function canGenerateBulletin10($id_classe, $id_cycle, $id_nniveau, $sequence) {
        // Récupérer les matières sans notes via une jointure
        $matieresSansNotes = DB::table('classe_matiere as cm')
            ->leftJoin('note as n', function ($join) use ($sequence) {
                $join->on('cm.id_matiere', '=', 'n.id_matiere')
                    ->where('n.annee_scolaire', '=', session('year')) // Ajout explicite de l'opérateur '='
                    ->where('n.sequence', '=', $sequence);            // Ajout explicite de l'opérateur '='
            })
            ->whereNull('n.id') // Filtrer les matières sans notes
            ->where('cm.id_classe', '=', $id_classe)
            ->where('cm.id_cycle', '=', $id_cycle)
            ->where('cm.id_nniveau', '=', $id_nniveau)
            ->where('cm.type', '>=', 0)
            ->get();

        // Récupérer toutes les matières
        $toutesMatieres = DB::table('classe_matiere')
            ->where('id_classe', '=', $id_classe)
            ->where('id_cycle', '=', $id_cycle)
            ->where('id_nniveau', '=', $id_nniveau)
            ->where('type', '>=', 0)
            ->get();

        // Vérifier si la liste de toutes les matières est vide en utilisant count() au lieu de isEmpty()
        if (count($toutesMatieres) === 0) {
            return [
                'status' => 'error',
                'message' => trans('eleve.cannot_generate_no_subjects'), // Aucun sujet trouvé
            ];
        }

        // Calculer les matières avec notes en comparant les collections
        $matieresAvecNotes = $toutesMatieres->diff($matieresSansNotes);

        return [
            'status' => 'success',
            'matieresAvecNotes' => $matieresAvecNotes, // Matières avec notes
            'matieresSansNotes' => $matieresSansNotes, // Matières sans notes
        ];
    }*/
    public function canGenerateBulletin($id_classe, $id_cycle, $id_nniveau, $sequence) {
        // Récupérer les matières sans notes via une jointure
        $matieresSansNotes = DB::table('classe_matiere as cm')
            ->leftJoin('note as n', function ($join) use ($sequence) {
                $join->on('cm.id_matiere', '=', 'n.id_matiere')
                    ->where('n.annee_scolaire', '=', session('year')) // Ajout explicite de l'opérateur '='
                    ->where('n.sequence', '=', $sequence);            // Ajout explicite de l'opérateur '='
            })
            ->whereNull('n.id') // Filtrer les matières sans notes
            ->where('cm.id_classe', '=', $id_classe)
            ->where('cm.id_cycle', '=', $id_cycle)
            ->where('cm.id_nniveau', '=', $id_nniveau)
            ->where('cm.type', '>=', 0)
            ->get();

        // Récupérer toutes les matières
        $toutesMatieres = DB::table('classe_matiere')
            ->where('id_classe', '=', $id_classe)
            ->where('id_cycle', '=', $id_cycle)
            ->where('id_nniveau', '=', $id_nniveau)
            ->where('type', '>=', 0)
            ->get();

        // Vérifier si la liste de toutes les matières est vide en utilisant count()
        if (count($toutesMatieres) === 0) {
            return [
                'status' => 'error',
                'message' => trans('eleve.cannot_generate_no_subjects'), // Aucun sujet trouvé
            ];
        }

        // Utiliser map() pour extraire 'id_matiere' des matières sans notes
        $matieresSansNotesIds = collect($matieresSansNotes)->map(function($item) {
            return $item->id_matiere;
        });

        // Calculer les matières avec notes
        $matieresAvecNotes = collect($toutesMatieres)->filter(function($matiere) use ($matieresSansNotesIds) {
            return !$matieresSansNotesIds->contains($matiere->id_matiere);
        });

        return [
            'status' => 'success',
            'matieresAvecNotes' => $matieresAvecNotes, // Matières avec notes
            'matieresSansNotes' => $matieresSansNotes, // Matières sans notes
        ];
    }

    public function canGenerateBulletinlouis($id_classe, $id_cycle, $id_nniveau, $sequence) {
        // Récupérer les matières sans notes via une jointure
        $matieresSansNotes = DB::table('classe_matiere as cm')
            ->leftJoin('note as n', function ($join) use ($sequence) {
                $join->on('cm.id_matiere', '=', 'n.id_matiere')
                    ->where('n.annee_scolaire', '=', session('year')) // Ajout explicite de l'opérateur '='
                    ->where('n.sequence', '=', $sequence);            // Ajout explicite de l'opérateur '='
            })
            ->whereNull('n.id') // Filtrer les matières sans notes
            ->where('cm.id_classe', '=', $id_classe)
            ->where('cm.id_cycle', '=', $id_cycle)
            ->where('cm.id_nniveau', '=', $id_nniveau)
            ->where('cm.type', '>=', 0)
            ->get();

        // Convertir les résultats en collection Laravel pour pouvoir utiliser diff() et whereIn()
        $matieresSansNotesCollection = collect($matieresSansNotes)->map('id_matiere');  // Extraire uniquement les id_matiere

        // Récupérer toutes les matières
        $toutesMatieres = DB::table('classe_matiere')
            ->where('id_classe', '=', $id_classe)
            ->where('id_cycle', '=', $id_cycle)
            ->where('id_nniveau', '=', $id_nniveau)
            ->where('type', '>=', 0)
            ->get();

        // Convertir les résultats en collection Laravel
        $toutesMatieresCollection = collect($toutesMatieres);

        // Vérifier si la liste de toutes les matières est vide
        if ($toutesMatieresCollection->isEmpty()) {
            return [
                'status' => 'error',
                'message' => trans('eleve.cannot_generate_no_subjects'), // Aucun sujet trouvé
            ];
        }

        // Calculer les matières avec notes en comparant les collections
        $matieresAvecNotes = $toutesMatieresCollection->map('id_matiere')->diff($matieresSansNotesCollection);

        // Maintenant, récupérez les matières correspondantes avec notes
        $matieresAvecNotesDetails = $toutesMatieresCollection->whereIn('id_matiere', $matieresAvecNotes);

        return [
            'status' => 'success',
            'matieresAvecNotes' => $matieresAvecNotesDetails, // Matières avec notes
            'matieresSansNotes' => $matieresSansNotes, // Matières sans notes
        ];
    }





    public function canGenerateBulletintouesMa($id_classe, $id_cycle, $id_nniveau, $sequence) {
        // Vérifier si les matières avec notes existent pour cette séquence
        $matieresAvecNotes = DB::select(
            'SELECT DISTINCT n.id_matiere 
        FROM note n 
        WHERE n.id_matiere IN (
            SELECT id_matiere 
            FROM classe_matiere cm 
            WHERE cm.id_classe = ? AND cm.id_cycle = ? AND cm.id_nniveau = ? AND cm.type >= 0
        )
        AND n.annee_scolaire = ? AND n.sequence = ?',
            [$id_classe, $id_cycle, $id_nniveau, session('year'), $sequence]
        );

        // Vérifier si le résultat est un tableau
        $matieresAvecNotesIds = [];
        if (is_array($matieresAvecNotes)) {
            foreach ($matieresAvecNotes as $matiere) {
                if (isset($matiere->id_matiere)) {
                    $matieresAvecNotesIds[] = $matiere->id_matiere;
                }
            }
        }

        // Récupérer toutes les matières de la classe
        $toutesMatieres = classeMatiere::where('id_classe', $id_classe)
            ->where('id_cycle', $id_cycle)
            ->where('id_nniveau', $id_nniveau)
            ->where('type', '>=', 0)
            ->pluck('id_matiere'); // Récupération en tant que collection

        if ($toutesMatieres instanceof Illuminate\Support\Collection) {
            // Si c'est une collection Laravel, convertissez-la en tableau
            $toutesMatieres = $toutesMatieres->toArray();
        } elseif (!is_array($toutesMatieres)) {
            // Si ce n'est ni une collection ni un tableau, gérez l'erreur
            $toutesMatieres = [];
        }

        // Identifier les matières sans notes
        $matieresSansNotes = array_diff($toutesMatieres, $matieresAvecNotesIds);

        if (empty($matieresAvecNotesIds)) {
            // Aucun bulletin ne peut être généré si aucune matière n'a de notes
            return [
                'status' => 'error',
                'message' => trans('eleve.cannot_generate_no_subjects')
            ];
        }

        return [
            'status' => 'success',
            'matieresSansNotes' => $matieresSansNotes // Renvoie les matières à ignorer
        ];
    }





   public function canGenerateBulletinmarche($id_classe, $id_cycle, $id_nniveau, $sequence) {
        // Compter les matières qui ont des notes pour la séquence
        $mat = DB::select(
            'SELECT COUNT(DISTINCT n.id_matiere) as nb 
        FROM note n 
        WHERE n.id_matiere IN (
            SELECT id_matiere 
            FROM classe_matiere cm 
            WHERE cm.id_classe = ? AND cm.id_cycle = ? AND cm.id_nniveau = ? AND cm.type >= 0
        )
        AND n.annee_scolaire = ? AND n.sequence = ? 
        AND n.id_eleve IN (
            SELECT e.id 
            FROM eleve e 
            JOIN inscription i ON e.id = i.id_eleve 
            WHERE i.annee_scolaire = ? AND i.id_classe = ? AND i.id_cycle = ? AND i.id_nniveau = ?
        )', [$id_classe, $id_cycle, $id_nniveau, session('year'), $sequence, session('year'), $id_classe, $id_cycle, $id_nniveau]
        );

        // Compter toutes les matières de la classe pour cette séquence
        $pro = classeMatiere::where('id_classe', $id_classe)
            ->where('id_cycle', $id_cycle)
            ->where('id_nniveau', $id_nniveau)
            ->where('type', '>=', 0)
            ->count();

        if (!empty($mat) && $mat[0]->nb < $pro) {
            // Récupérer les matières sans notes pour la séquence
            $mats = DB::select(
                'SELECT m.nom, m.nom_anglais 
            FROM matiere m 
            JOIN classe_matiere cm ON cm.id_matiere = m.id 
            WHERE cm.id_classe = ? AND cm.id_cycle = ? AND cm.id_nniveau = ? AND cm.type >= 0 
            AND m.id NOT IN (
                SELECT DISTINCT n.id_matiere 
                FROM note n 
                WHERE n.annee_scolaire = ? AND n.sequence = ? 
                AND n.id_eleve IN (
                    SELECT e.id 
                    FROM eleve e 
                    JOIN inscription i ON e.id = i.id_eleve 
                    WHERE i.annee_scolaire = ? AND i.id_classe = ? AND i.id_cycle = ? AND i.id_nniveau = ?
                )
            )',
                [$id_classe, $id_cycle, $id_nniveau, session('year'), $sequence, session('year'), $id_classe, $id_cycle, $id_nniveau]
            );

            return $mats; // Renvoie les matières sans notes
        } else {
            return true; // Toutes les matières ont des notes
        }
    }

    public function canGenerateBulletin2($id_classe, $id_cycle, $id_nniveau, $sequence)
    {
        // Récupérer les matières qui ont des notes pour la séquence donnée
        $matieresAvecNotes = DB::select(
            'SELECT DISTINCT m.id, m.nom, m.nom_anglais
         FROM matiere m
         JOIN classe_matiere cm ON cm.id_matiere = m.id
         JOIN note n ON n.id_matiere = m.id
         JOIN inscription i ON i.id_eleve = n.id_eleve
         WHERE cm.id_classe = ? 
         AND cm.id_cycle = ? 
         AND cm.id_nniveau = ? 
         AND cm.type >= 0
         AND n.annee_scolaire = ? 
         AND n.sequence = ?
         AND i.id_classe = ?
         AND i.id_cycle = ? 
         AND i.id_nniveau = ?',
            [
                $id_classe,
                $id_cycle,
                $id_nniveau,
                session('year'),
                $sequence,
                $id_classe,
                $id_cycle,
                $id_nniveau,
            ]
        );

        if (empty($matieresAvecNotes)) {
            return response()->json(['error' => trans('eleve.no_notes_for_sequence')], 400);
        }

        // Vérifier le nombre de matières attendues pour cette classe, cycle et niveau
        $totalMatieres = classeMatiere::where('id_classe', $id_classe)
            ->where('id_cycle', $id_cycle)
            ->where('id_nniveau', $id_nniveau)
            ->where('type', '>=', 0)
            ->count();


        // Retourner uniquement les matières ayant des notes
        if (count($matieresAvecNotes) < $totalMatieres) {
            return $matieresAvecNotes; // Les matières avec des notes pour traitement ultérieur
        }
        // Si toutes les matières attendues ont des notes, continuer avec la génération
        return true;
        // Si toutes les matières attendues ont des notes, retourner true
        /*   if (count($matieresAvecNotes) === $totalMatieres) {
               return true;
           }

           // Retourner la liste des matières avec des notes
           return $matieresAvecNotes;
       }*/
    }





    public function canGenerateBulletin1($id_classe,$id_cycle,$id_nniveau,$sequence){
       // $mat=DB::select('SELECT COUNT(DISTINCT n.id_matiere) as nb FROM note n WHERE n.id_matiere IN (SELECT id_matiere FROM classe_matiere cm WHERE id_classe=? AND type>=0 AND  id_cycle=? AND id_nniveau=?) AND  annee_scolaire=? AND sequence=? AND n.id_eleve IN(SELECT e.id FROM eleve e,inscription i WHERE annee_scolaire=? AND e.id=i.id_eleve AND i.id_classe=? AND i.id_cycle=? AND i.id_nniveau=?)',[$id_classe,session('year'),$sequence,session('year'),$id_classe,$id_cycle,$id_nniveau]);
    /*    $mat = DB::select(
            'SELECT COUNT(DISTINCT n.id_matiere) as nb
     FROM note n
     WHERE n.id_matiere IN (
         SELECT id_matiere
         FROM classe_matiere cm
         WHERE id_classe=? AND type>=0 AND id_cycle=? AND id_nniveau=?
     )
     AND annee_scolaire=?
     AND sequence=?
     AND n.id_eleve IN (
         SELECT e.id
         FROM eleve e, inscription i
         WHERE annee_scolaire=? AND e.id=i.id_eleve AND i.id_classe=? AND i.id_cycle=? AND i.id_nniveau=?)', [$id_classe, session('year'), $id_nniveau, session('year'), $sequence, session('year'), $id_classe, $id_cycle, $id_nniveau]);
*/
      /*  $mat = DB::table('note as n')
            ->selectRaw('COUNT(DISTINCT n.id_matiere) as nb')
            ->whereIn('n.id_matiere', function ($query) use ($id_classe, $id_cycle, $id_nniveau) {
                $query->select('id_matiere')
                    ->from('classe_matiere')
                    ->where('id_classe', $id_classe)
                    ->where('type', '>=', 0)
                    ->where('id_cycle', $id_cycle)
                    ->where('id_nniveau', $id_nniveau);
            })
            ->where('annee_scolaire', session('year'))
            ->where('sequence', $sequence)
            ->whereIn('n.id_eleve', function ($query) use ($id_classe, $id_cycle, $id_nniveau) {
                $query->select('e.id')
                    ->from('eleve as e')
                    ->join('inscription as i', 'e.id', '=', 'i.id_eleve')
                    ->where('annee_scolaire', session('year'))
                    ->where('i.id_classe', $id_classe)
                    ->where('i.id_cycle', $id_cycle)
                    ->where('i.id_nniveau', $id_nniveau);
            })
            ->get();*/

        $mat = DB::select(
            'SELECT COUNT(DISTINCT n.id_matiere) as nb 
     FROM note n 
     WHERE n.id_matiere IN (
         SELECT id_matiere 
         FROM classe_matiere cm 
         WHERE cm.id_classe=? AND cm.id_cycle=? AND cm.id_nniveau=? AND cm.type >= 0
     )
     AND n.annee_scolaire=? AND n.sequence=? 
     AND n.id_eleve IN (
         SELECT e.id 
         FROM eleve e 
         JOIN inscription i ON e.id = i.id_eleve 
         WHERE i.annee_scolaire=? AND i.id_classe=? AND i.id_cycle=? AND i.id_nniveau=? )', [$id_classe, $id_cycle, $id_nniveau, session('year'), $sequence, session('year'), $id_classe, $id_cycle, $id_nniveau]);

        /*     $params = [
             'id_classe' => $id_classe,
             'id_cycle' => $id_cycle,
             'id_nniveau' => $id_nniveau,
             'annee_scolaire' => session('year'),
             'sequence' => $sequence,
         ];

     $mat = DB::table('note as n')
             ->selectRaw('COUNT(DISTINCT n.id_matiere) as nb')
             ->whereIn('n.id_matiere', function ($query) use ($params) {
                 $query->select('id_matiere')
                     ->from('classe_matiere')
                     ->where('id_classe', $params['id_classe'])
                     ->where('type', '>=', 0)
                     ->where('id_cycle', $params['id_cycle'])
                     ->where('id_nniveau', $params['id_nniveau']);
             })
             ->where('annee_scolaire', $params['annee_scolaire'])
             ->where('sequence', $params['sequence'])
             ->whereIn('n.id_eleve', function ($query) use ($params) {
                 $query->select('e.id')
                     ->from('eleve as e')
                     ->join('inscription as i', 'e.id', '=', 'i.id_eleve')
                     ->where('annee_scolaire', $params['annee_scolaire'])
                     ->where('i.id_classe', $params['id_classe'])
                     ->where('i.id_cycle', $params['id_cycle'])
                     ->where('i.id_nniveau', $params['id_nniveau']);
             })
             ->first();


	    $pro=classeMatiere::whereIdClasse($id_classe)
            ->where('id_cycle','=',$id_cycle)
            ->where('id_cycle','=',$id_nniveau)
            ->where('type','>=',0)
            ->get()->count();*/
        $pro = classeMatiere::where('id_classe', $id_classe)
            ->where('id_cycle', $id_cycle)
            ->where('id_nniveau', $id_nniveau)
            ->where('type', '>=', 0)
            ->count();

        if (!empty($mat) && $mat[0]->nb < $pro) {
            $mats = DB::select(
                'SELECT m.nom, m.nom_anglais 
         FROM matiere m 
         JOIN classe_matiere cm ON cm.id_matiere = m.id 
         WHERE cm.id_classe = ? AND cm.id_cycle = ? AND cm.id_nniveau = ? AND cm.type >= 0 
         AND m.id NOT IN (
             SELECT DISTINCT n.id_matiere 
             FROM note n 
             WHERE n.annee_scolaire = ? AND n.sequence = ? 
             AND n.id_eleve IN (
                 SELECT e.id 
                 FROM eleve e 
                 JOIN inscription i ON e.id = i.id_eleve 
                 WHERE i.annee_scolaire = ? AND i.id_classe = ? AND i.id_cycle = ? AND i.id_nniveau = ?
             )
         )',
                [$id_classe, $id_cycle, $id_nniveau, session('year'), $sequence, session('year'), $id_classe, $id_cycle, $id_nniveau]);

            //$mats=DB::select('SELECT m.nom,m.nom_anglais FROM matiere m ,classe_matiere cm  WHERE cm.id_classe=?  AND  id_cycle=? AND id_nniveau=? AND cm.id_matiere=m.id  AND m.id NOT IN(SELECT DISTINCT n.id_matiere as nb FROM note n WHERE annee_scolaire=? AND sequence=? AND n.id_eleve IN(SELECT e.id FROM eleve e,inscription i WHERE annee_scolaire=? AND e.id=i.id_eleve AND i.id_classe=? i.id_cycle=? i.id_nniveau=? ))',[$id_classe,session('year'),$sequence,session('year'),$id_classe,$id_cycle,$id_nniveau]);
            return $mats;
        }else
            return true;

    }

    public function canGenerateBulletinTrim($id_classe ,$id_cycle,$id_nniveau,$sequence){
	    if($sequence==9) {
            $sequence1 = 1;
            $sequence2 = 2;
            //$sequence3 = 3;
        }
        elseif ($sequence==10){
            $sequence1 = 4;
            $sequence2 = 5;
            $sequence3 = 6;
        }
        elseif ($sequence==11){
            $sequence1 = 7;
            $sequence2 = 8;
            $sequence3 = '';
        }

        /*$mat=DB::select('SELECT COUNT(DISTINCT n.id_matiere) as nb FROM note n WHERE annee_scolaire=? AND (sequence=? OR sequence=?) AND n.id_eleve IN(SELECT e.id FROM eleve e,inscription i WHERE annee_scolaire=? AND e.id=i.id_eleve AND i.id_classe=?)',[session('year'),$sequence1,$sequence2,session('year'),$id_classe]);
        $pro=classeMatiere::whereIdClasse($id_classe)->get()->count();

        if($mat['0']->nb<$pro){
            $mats=DB::select('SELECT m.nom,m.nom_anglais FROM matiere m ,classe_matiere cm  WHERE cm.id_classe=? AND cm.id_matiere=m.id AND m.id NOT IN(SELECT DISTINCT n.id_matiere as nb FROM note n WHERE annee_scolaire=? AND (sequence=? OR sequence=?)',[$id_classe,session('year'),$sequence1,$sequence2]);
            return $mats;
        }else
            return true;*/
             if ($sequence3!=null){
            $bg1=bulletin_genere::whereAnneeScolaire(session('year'))
                ->where('id_classe','=',$id_classe)
                ->where('id_cycle','=',$id_cycle)
                ->where('id_nniveau','=',$id_nniveau)
                ->where('sequence','=',$sequence1)
                ->first();
            $bg2=bulletin_genere::whereAnneeScolaire(session('year'))
                ->where('id_classe','=',$id_classe)
                ->where('id_cycle','=',$id_cycle)
                ->where('id_nniveau','=',$id_nniveau)
                ->where('sequence','=',$sequence2)->first();
            $bg3=bulletin_genere::whereAnneeScolaire(session('year'))
                ->where('id_classe','=',$id_classe)
                ->where('id_cycle','=',$id_cycle)
                ->where('id_nniveau','=',$id_nniveau)
                ->where('sequence','=',$sequence3)
                ->first();

            if(($bg1==null) || ($bg2==null)|| ($bg3==null) ){
                $val=[];
                if(($bg1==null))
                    $val[0]=$sequence1;
                if(($bg2==null))
                    $val[1]=$sequence2;
                if(($bg3==null))
                    $val[2]=$sequence3;
                return $val;
            }else
                return true;
             }else{
                 $bg1=bulletin_genere::whereAnneeScolaire(session('year'))
                     ->where('id_classe','=',$id_classe)
                     ->where('id_cycle','=',$id_cycle)
                     ->where('id_nniveau','=',$id_nniveau)
                     ->where('sequence','=',$sequence1)
                     ->first();
                 $bg2=bulletin_genere::whereAnneeScolaire(session('year'))
                     ->where('id_classe','=',$id_classe)
                     ->where('id_cycle','=',$id_cycle)
                     ->where('id_nniveau','=',$id_nniveau)
                     ->where('sequence','=',$sequence2)
                     ->first();
                 if(($bg1==null) || ($bg2==null) ){
                     $val=[];
                     if(($bg1==null))
                         $val[0]=$sequence1;
                     if(($bg2==null))
                         $val[1]=$sequence2;
                     return $val;
                 }else
                     return true;
             }
        /*elseif ($sequence3=null)
        {
            $bg1=bulletin_genere::whereAnneeScolaire(session('year'))->where('id_classe','=',$id_classe)->where('sequence','=',$sequence1)->first();
            $bg2=bulletin_genere::whereAnneeScolaire(session('year'))->where('id_classe','=',$id_classe)->where('sequence','=',$sequence2)->first();
            if(($bg1==null) || ($bg2==null) ){
                $val=[];
                if(($bg1==null))
                    $val[0]=$sequence1;
                if(($bg2==null))
                    $val[1]=$sequence2;
                return $val;
            }else
                return true;
        }*/
        }






    public function canGenerateBulletinAnnuel($id_classe, $id_cycle, $id_nniveau,$sequence){
        if($sequence==12) {
            $sequence1 = 9;
            $sequence2 = 10;
            $sequence3 = 11;
        }


        $bg1=bulletin_genere::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$id_classe)
            ->where('id_cycle','=',$id_cycle)
            ->where('id_nniveau','=',$id_nniveau)
            ->where('sequence','=',$sequence1)
             ->first();
        $bg2=bulletin_genere::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$id_classe)
            ->where('id_cycle','=',$id_cycle)
            ->where('id_nniveau','=',$id_nniveau)
            ->where('sequence','=',$sequence2)
            ->first();
        $bg3=bulletin_genere::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$id_classe)
            ->where('id_cycle','=',$id_cycle)
            ->where('id_nniveau','=',$id_nniveau)
            ->where('sequence','=',$sequence3)
            ->first();


        if(($bg1==null) || ($bg2==null) || ($bg3==null)){
            $val=[];
            if(($bg1==null))
                $val[0]=1;
            if(($bg2==null))
                $val[1]=2;
            if(($bg3==null))
                $val[2]=3;
            return $val;
        }else
            return true;

    }

    public function generateBulletin5($id_classe, $id_cycle, $id_nniveau, $sequence) {
        $startGen = microtime(true);

        // Vérifier les matières sans notes
        // Vérification des matières
        $validation = $this->canGenerateBulletin($id_classe, $id_cycle, $id_nniveau, $sequence);

        if ($validation['status'] === 'error') {
            return response()->json(['error' => $validation['message']], 500);
        }
        $matieresSansNotes = $validation['matieresSansNotes'];
        $ignoredSubjects = matiere::whereIn('id', $matieresSansNotes)->pluck('nom')->toArray();

    return response()->json([
        'success' => trans('eleve.bulletin_generated'),
        'ignored_subjects' => $ignoredSubjects
    ], 200);
       /* $missingSubjects = $this->canGenerateBulletin($id_classe, $id_cycle, $id_nniveau, $sequence);


if ($missingSubjects !== true) {
            // Construire un message d'erreur pour les matières manquantes
            $str = '';
            foreach ($missingSubjects as $m) {
                $str .= ((\session('locale') == 'en') ? $m->nom_anglais : $m->nom) . ', ';
            }
            $err_msg = trans('eleve.cannot_generate') . ' ' . $str;
            return response()->json(['error' => $err_msg], 500);
        }*/

        // Générer les bulletins uniquement pour les matières ayant des notes
        $students = inscription::where('id_classe', $id_classe)
            ->where('id_cycle', $id_cycle)
            ->where('id_nniveau', $id_nniveau)
            ->where('annee_scolaire', session('year'))
            ->pluck('id_eleve');

        foreach ($students as $student_id) {
            $grades = DB::table('note')
                ->join('classe_matiere', 'note.id_matiere', '=', 'classe_matiere.id_matiere')
                ->where('note.id_eleve', $student_id)
                ->where('classe_matiere.id_classe', $id_classe)
                ->where('classe_matiere.id_cycle', $id_cycle)
                ->where('classe_matiere.id_nniveau', $id_nniveau)
                ->where('note.sequence', $sequence)
                ->select('note.*')
                ->get();

            if ($grades->isEmpty()) {
                continue; // Ignorer les élèves sans notes
            }

            // Appel à une méthode fictive pour enregistrer/générer les bulletins
            $this->saveBulletin($student_id, $grades);
        }

        $endGen = microtime(true);
        return response()->json(['success' => trans('eleve.bulletin_generated')], 200);
    }


    public function generateBulletin($id_classe,$id_cycle,$id_nniveau,$sequence){
	    $cm=classeMatiere::whereIdClasse($id_classe)
            ->where('id_cycle','=',$id_cycle)
            ->where('id_nniveau','=',$id_nniveau)
            ->get();
	    $e=eleve::join('inscription','eleve.id','=','inscription.id_eleve')
            ->where('annee_scolaire','=',session('year'))
            ->where('id_classe','=',$id_classe)
            ->where('id_cycle','=',$id_cycle)
            ->where('id_nniveau','=',$id_nniveau)
            ->select(['eleve.*'])
            ->get();
	    $e_array=$e->fetch('id')->toArray();
	    DB::delete('DELETE FROM moyenne_matiere WHERE id_classe=? AND id_cycle=? AND id_nniveau=? AND annee_scolaire=? AND sequence=?',[$id_classe,$id_cycle,$id_nniveau,\session('year'),$sequence]);
	    DB::delete('DELETE FROM moyenne_eleve WHERE id_classe=? AND id_cycle=? AND id_nniveau=?  AND annee_scolaire=? AND sequence=?',[$id_classe,$id_cycle,$id_nniveau,\session('year'),$sequence]);
         $n=note::join('classe_matiere','note.id_matiere','=','classe_matiere.id_matiere')
             ->where('id_classe','=',$id_classe)
             ->where('id_cycle','=',$id_cycle)
             ->where('id_nniveau','=',$id_nniveau)
             ->where('annee_scolaire','=',session('year'))
             ->where('sequence','=',$sequence)
             ->whereIn('id_eleve',$e_array)
             ->select(['note.*','coef'])
            ->get();
         dump($n);
         die();
        //$e=DB::select('SELECT e.id,e.nom,e.prenom FROM eleve e,inscription i WHERE annee_scolaire=? AND i.id_classe=? AND e.id=i.id_eleve',[session('year'),$id_classe] );
        //$n=DB::select('SELECT * FROM note n WHERE annee_scolaire=?  AND sequence=? AND n.id_eleve IN(SELECT e.id FROM eleve e,inscription i WHERE annee_scolaire=? AND i.id_classe=? AND e.id=i.id_eleve)',[session('year'),$sequence,session('year'),$id_classe] );
        //moyenne matiere

        $mmt=microtime(true);
        foreach ($cm as $m){

            $nbNote=0;
            $snote=0;
            $min=1000000;
            $max=0;

            //$n_temp=$n->where('id_matiere',$m->id_matiere);
            foreach ($n as $note){
                    if($note->id_matiere==$m->id_matiere)
                    {
                        if($note->note>$max)
                            $max=$note->note;
                        if($note->note<$min)
                            $min=$note->note;

                        $nbNote++;
                        $snote+=$note->note;

                    }
            }
            if( !($nbNote<=0)) {
                $mm=new moyenne_matiere();
                $mm->annee_scolaire = \session('year');
                $mm->id_matiere = $m->id_matiere;
                $mm->id_classe = $m->id_classe;
                $mm->id_cycle= $m->id_cycle;
                $mm->id_nniveau=$m->id_nniveau;
                $mm->sequence = $sequence;
                $mm->min = $min;
                $mm->id_classe = $id_classe;
                $mm->id_matiere = $m->id_matiere;
                $mm->max = $max;
                $mm->sequence = $sequence;
                $mm->annee_scolaire = session('year');
                $mm->moyenne = ($snote / $nbNote);
                $mm->save();
            }
        }
        //echo 'mm= '.(microtime(true)-$mmt).'<br>';

        //moyenne eleve
        $min_moy=1000000;
        $max_moy=0;
        $nb_moy=0;
        $sMoy=0;
        $nbNote1=0;

        foreach ($e as $eleve){
            $sNote=0;
            $sCoef=0;
            $n_temp=$n->where('id_eleve',$eleve->id);

                foreach ($n_temp as $note){
                    if( $eleve->id==$note->id_eleve){
                        $sNote+=$note->note;
                      //  $sNote+=$note->note*$note->coef;
                        $sCoef+=$note->coef;
                        $nbNote1++;
                    }
                }


            $me=new moyenne_eleve();
            $me->annee_scolaire = \session('year');
            $me->id_eleve = $eleve->id;
            $me->id_classe = $id_classe;
            $me->id_cycle= $id_cycle;
            $me->id_nniveau=$id_nniveau;
            $me->sequence = $sequence;


            if($sCoef==0)
                $sCoef=1;
          //  $moy=$sNote/$sCoef;
            $moy=$sNote/$nbNote1;

            $nb_moy++;
            $sMoy+=$moy;

            $me=new moyenne_eleve();
            $me->annee_scolaire = \session('year');
            $me->id_eleve = $eleve->id;
            $me->id_classe = $id_classe;
            $me->id_cycle= $id_cycle;
            $me->id_nniveau=$id_nniveau;
            $me->sequence = $sequence;

            $me->moyenne=$moy;
            $me->save();
            if($moy>$max_moy)
                $max_moy=$moy;
            if($moy<$min_moy)
                $min_moy=$moy;
        }
        $mc=moyenne_classe::firstOrNew(['annee_scolaire'=>session('year'),'id_classe'=>$id_classe, 'id_cycle'=>$id_cycle,'id_nniveau'=>$id_nniveau,'sequence'=>$sequence]);
        $mc->min=$min_moy;
        $mc->max=$max_moy;
        $mc->moyenne=$sMoy/$nb_moy;
        $mc->id_cycle=$id_cycle;
        $mc->id_nniveau=$id_nniveau;
        $mc->save();

        $bg=bulletin_genere::firstOrNew(['id_classe'=>$id_classe, 'id_cycle'=>$id_cycle,'id_nniveau'=>$id_nniveau, 'annee_scolaire'=>session('year'),'sequence'=>$sequence]);
        $bg->id_cycle=$id_cycle;
        $bg->id_nniveau=$id_nniveau;
         $bg->save();
    }

    public function generateBulletinTrim($id_classe,$id_cycle,$id_nniveau,$sequence){
        if($sequence==9) {
            $sequence1 = 1;
            $sequence2 = 2;
            $sequence3 = 3;
        }
        elseif ($sequence==10){
            $sequence1 = 4;
            $sequence2 = 5;
            $sequence3 = 6;
        }
        else{
            $sequence1 = 7;
            $sequence2 = 8;
            $sequence3 = '';

        }
        $cm=classeMatiere::whereIdClasse($id_classe)
            ->where('id_cycle','=',$id_cycle)
            ->where('id_nniveau','=',$id_nniveau)
            ->get();
        $e=eleve::join('inscription','eleve.id','=','inscription.id_eleve')
            ->where('annee_scolaire','=',session('year'))
            ->where('id_classe','=',$id_classe)
            ->where('id_cycle','=',$id_cycle)
            ->where('id_nniveau','=',$id_nniveau)
            ->select(['eleve.*'])
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();
        $e_array=$e->fetch('id')->toArray();

        $ntd=note::whereRaw('annee_scolaire=? AND sequence=?',[session('year'),$sequence])
            ->whereIn('id_eleve',$e_array)
            ->select(['note.id'])
            ->get();
        $a_ntd=$ntd->fetch('id')->toArray();
        note::destroy($a_ntd);
        DB::delete('DELETE FROM moyenne_matiere WHERE id_classe=?  AND id_cycle= ? AND id_nniveau=? AND annee_scolaire=? AND sequence=?',[$id_classe,$id_cycle,$id_nniveau,\session('year'),$sequence]);
        DB::delete('DELETE FROM moyenne_eleve WHERE id_classe=? AND id_cycle=? AND id_nniveau=? AND  annee_scolaire=? AND sequence=?',[$id_classe,$id_cycle,$id_nniveau,\session('year'),$sequence]);

        if ($sequence3!=null){
        $n=note::join('classe_matiere','note.id_matiere','=','classe_matiere.id_matiere')
            ->whereRaw('id_classe=? AND id_cycle=? AND id_nniveau=?  AND annee_scolaire=? AND (sequence=? OR sequence=? OR sequence=?)',[$id_classe ,$id_cycle,$id_nniveau,session('year'),$sequence1,$sequence2,$sequence3])
            ->whereIn('id_eleve',$e_array)
            ->select(['note.*','coef'])
            ->get();
        }
            else{
                $n=note::join('classe_matiere','note.id_matiere','=','classe_matiere.id_matiere')
                    ->whereRaw('id_classe=?  AND id_cycle=? AND id_nniveau=? AND annee_scolaire=? AND (sequence=? OR sequence=?)',[$id_classe,$id_cycle,$id_nniveau,session('year'),$sequence1,$sequence2])
                    ->whereIn('id_eleve',$e_array)
                    ->select(['note.*','coef'])
                    ->get();}

        //$e=DB::select('SELECT e.id,e.nom,e.prenom FROM eleve e,inscription i WHERE annee_scolaire=? AND i.id_classe=? AND e.id=i.id_eleve',[session('year'),$id_classe] );
        //$n=DB::select('SELECT * FROM note n WHERE annee_scolaire=?  AND (sequence=? OR sequence=?) AND n.id_eleve IN(SELECT e.id FROM eleve e,inscription i WHERE annee_scolaire=? AND i.id_classe=? AND e.id=i.id_eleve)',[session('year'),$sequence1,$sequence2,session('year'),$id_classe] );

        //moyenne matiere

        $mmt=microtime(true);

        foreach ($cm as $m){

            $nbNote=0;
            $snote=0;
            $min=1000000;
            $max=0;

            $n_temp=$n->where('id_matiere',$m->id_matiere);

            foreach ($e as $eleve){
                $n_temp0=$n_temp->where('id_eleve',$eleve->id);

                $sNote0=0;
                $nbNote0=0;
                foreach ($n_temp0 as $note){

                    if(($m->id_matiere==$note->id_matiere) && ($eleve->id==$note->id_eleve)) {
                        $sNote0 += $note->note;
                        $nbNote0++;
                    }
                }
                if($nbNote0>0){
                    $noteDef=$sNote0/$nbNote0;
                    if($noteDef>$max)
                        $max=$noteDef;
                    if($noteDef<$min)
                        $min=$noteDef;

                    $nbNote++;
                    $snote+=$noteDef;
                }
            }

        if($nbNote>0) {
            $mm=new moyenne_matiere();
            $mm->annee_scolaire = \session('year');
            $mm->id_matiere = $m->id_matiere;
            $mm->id_classe = $m->id_classe;
            $mm->id_cycle = $m->id_cycle;
            $mm->id_nniveau = $m->id_nniveau;
            $mm->sequence = $sequence;
            $mm->min = $min;
            $mm->id_classe = $id_classe;
            $mm->id_matiere = $m->id_matiere;
            $mm->max = $max;
            $mm->sequence = $sequence;
            $mm->annee_scolaire = session('year');
            $mm->moyenne = ($snote / $nbNote);
            $mm->save();
        }
        }
        //echo 'mm= '.(microtime(true)-$mmt).'<br>';


        //moyenne eleve
        $min_moy=1000000;
        $max_moy=0;
        $nb_moy=0;
        $sMoy=0;
        foreach ($e as $eleve){
            $sNote=0;
            $sCoef=0;
            $n_temp=$n->where('id_eleve',$eleve->id);



        /*   $not=note::join('classe_matiere','note.id_matiere','=','classe_matiere.id_matiere')
                ->whereRaw('id_classe=? AND annee_scolaire=? AND (sequence=? OR sequence=?) AND id_eleve=?',[$id_classe,session('year'),$sequence1,$sequence2,$eleve->id])
                ->select(['note.*','coef'])
                ->orderBy('id_matiere')
                ->get();
            $old_idm=0;
            $old_coef=0;
            $sNote0=0;
            $nbNote0=0;
            foreach ($not as $note){
                if($old_idm!=$note->id_matiere && $old_idm!=0){

                    if($nbNote0>0){
                        $sNote+=($sNote0/$nbNote0)*$old_coef;
                        $sCoef+=$old_coef;

                       $n1=new note();
                       $n1->annee_scolaire=\session('year');
                       $n1->id_matiere=$old_idm;
                       $n1->sequence=$sequence;
                       $n1->id_eleve=$eleve->id;
                        $n1->note=($sNote0/$nbNote0);
                        $n1->save();

                        $sNote0=0;
                        $nbNote0=0;
                    }
                }
                $old_idm=$note->id_matiere;
                $old_coef=$note->coef;
                $sNote0+=$note->note;
                $nbNote0++;
            }*/



            foreach ($cm as $m){
                $n_temp0=$n_temp->where('id_matiere',$m->id_matiere);
                $sNote0=0;
                $nbNote0=0;
                foreach ($n_temp0 as $note){
                    if(($m->id_matiere==$note->id_matiere) && ($eleve->id==$note->id_eleve)){
                        $sNote0+=$note->note;
                        $nbNote0++;

                    }

                }
                if($nbNote0>0){
                    $sNote+=($sNote0/$nbNote0)*$m->coef;
                    $sCoef+=$m->coef;

                    $n1=new note();
                    $n1->annee_scolaire=\session('year');
                    $n1->id_matiere=$m->id_matiere;
                    $n1->sequence=$sequence;
                    $n1->id_eleve=$eleve->id;
                    $n1->note=($sNote0/$nbNote0);
                    $n1->save();
                }
            }


            if($sCoef==0)
                $sCoef=1;
            $moy=$sNote/$sCoef;
           // echo ($moy.'<br>');

            $nb_moy++;
            $sMoy+=$moy;

            $me=new moyenne_eleve();
            $me->annee_scolaire = \session('year');
            $me->id_eleve = $eleve->id;
            $me->id_classe = $id_classe;
            $me->id_cycle = $id_cycle;
            $me->id_nniveau = $id_nniveau;
            $me->sequence = $sequence;
            $me->moyenne=$moy;
            $me->save();
            if($moy>$max_moy)
                $max_moy=$moy;
            if($moy<$min_moy)
                $min_moy=$moy;
        }
        $mc=moyenne_classe::firstOrNew(['annee_scolaire'=>session('year'),'id_classe'=>$id_classe, 'id_cycle'=>$id_cycle,'id_nniveau'=>$id_nniveau,'sequence'=>$sequence]);
        $mc->min=$min_moy;
        $mc->max=$max_moy;
        $mc->moyenne=$sMoy/$nb_moy;
        $mc->save();
        $bg=bulletin_genere::firstOrNew(['id_classe'=>$id_classe,'id_cycle'=>$id_cycle,'id_nniveau'=>$id_nniveau,'annee_scolaire'=>session('year'),'sequence'=>$sequence]);
        $bg->save();
    }

    public function generateBulletinAnnuel($id_classe,$id_cycle,$id_nniveau,$sequence){

            $sequence1 = 9;
            $sequence2 = 10;
            $sequence3 = 11;




        $cm=classeMatiere::whereIdClasse($id_classe)->get();
        $e=eleve::join('inscription','eleve.id','=','inscription.id_eleve')
            ->where('annee_scolaire','=',session('year'))
            ->where('id_classe','=',$id_classe)
            ->where('id_cycle','=',$id_cycle)
            ->where('id_nniveau','=',$id_nniveau)
            ->select(['eleve.*'])
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();
        $e_array=$e->fetch('id')->toArray();

        $ntd=note::whereRaw('annee_scolaire=? AND sequence=?',[session('year'),$sequence])
            ->whereIn('id_eleve',$e_array)
            ->select(['note.id'])
            ->get();
        $a_ntd=$ntd->fetch('id')->toArray();
        note::destroy($a_ntd);
        DB::delete('DELETE FROM moyenne_matiere WHERE id_classe=? AND id_cycle=? AND id_nniveau=?  AND annee_scolaire=? AND sequence=?',[$id_classe,$id_cycle,$id_nniveau,\session('year'),$sequence]);
        DB::delete('DELETE FROM moyenne_eleve WHERE id_classe=? AND id_cycle=? AND id_nniveau=? AND annee_scolaire=? AND sequence=?',[$id_classe,$id_cycle,$id_nniveau,\session('year'),$sequence]);


        $n=note::join('classe_matiere','note.id_matiere','=','classe_matiere.id_matiere')
            ->whereRaw('id_classe=?  AND id_cycle=? AND id_nniveau=? AND annee_scolaire=? AND (sequence=? OR sequence=? OR sequence=?)',[$id_classe,$id_cycle,$id_nniveau,session('year'),$sequence1,$sequence2,$sequence3])
            ->whereIn('id_eleve',$e_array)
            ->select(['note.*','coef'])
            ->get();
        //$e=DB::select('SELECT e.id,e.nom,e.prenom FROM eleve e,inscription i WHERE annee_scolaire=? AND i.id_classe=? AND e.id=i.id_eleve',[session('year'),$id_classe] );
        //$n=DB::select('SELECT * FROM note n WHERE annee_scolaire=?  AND (sequence=? OR sequence=? OR sequence=?) AND n.id_eleve IN(SELECT e.id FROM eleve e,inscription i WHERE annee_scolaire=? AND i.id_classe=? AND e.id=i.id_eleve)',[session('year'),$sequence1,$sequence2,$sequence3,session('year'),$id_classe] );

        //moyenne matiere

        $mmt=microtime(true);

        foreach ($cm as $m){

            $nbNote=0;
            $snote=0;
            $min=1000000;
            $max=0;
            $n_temp=$n->where('id_matiere',$m->id_matiere);

            foreach ($e as $eleve){
                $n_temp0=$n_temp->where('id_eleve',$eleve->id);
                $sNote0=0;
                $nbNote0=0;
                foreach ($n_temp0 as $note){

                    if(($m->id_matiere==$note->id_matiere) && ($eleve->id==$note->id_eleve)) {
                        $sNote0 += $note->note;
                        $nbNote0++;
                    }
                }
                if($nbNote0>0){
                    $noteDef=$sNote0/$nbNote0;
                    if($noteDef>$max)
                        $max=$noteDef;
                    if($noteDef<$min)
                        $min=$noteDef;

                    $nbNote++;
                    $snote+=$noteDef;
                }
            }

            if($nbNote>0) {
                $mm=new moyenne_matiere();
                $mm->annee_scolaire = \session('year');
                $mm->id_matiere = $m->id_matiere;
                $mm->id_classe = $m->id_classe;
                $mm->id_cycle = $m->id_cycle;
                $mm->id_nniveau = $m->id_nniveau;
                $mm->sequence = $sequence;
                $mm->min = $min;
                $mm->id_classe = $id_classe;
                $mm->id_matiere = $m->id_matiere;
                $mm->max = $max;
                $mm->sequence = $sequence;
                $mm->annee_scolaire = session('year');
                $mm->moyenne = ($snote / $nbNote);
                $mm->save();
            }


        }
        //echo 'mm= '.(microtime(true)-$mmt).'<br>';


        //moyenne eleve
        $min_moy=1000000;
        $max_moy=0;
        $nb_moy=0;
        $sMoy=0;
        foreach ($e as $eleve){
            $sNote=0;
            $sCoef=0;
            $n_temp=$n->where('id_eleve',$eleve->id);



            /*   $not=note::join('classe_matiere','note.id_matiere','=','classe_matiere.id_matiere')
                    ->whereRaw('id_classe=? AND annee_scolaire=? AND (sequence=? OR sequence=?) AND id_eleve=?',[$id_classe,session('year'),$sequence1,$sequence2,$eleve->id])
                    ->select(['note.*','coef'])
                    ->orderBy('id_matiere')
                    ->get();
                $old_idm=0;
                $old_coef=0;
                $sNote0=0;
                $nbNote0=0;
                foreach ($not as $note){
                    if($old_idm!=$note->id_matiere && $old_idm!=0){

                        if($nbNote0>0){
                            $sNote+=($sNote0/$nbNote0)*$old_coef;
                            $sCoef+=$old_coef;

                           $n1=new note();
                           $n1->annee_scolaire=\session('year');
                           $n1->id_matiere=$old_idm;
                           $n1->sequence=$sequence;
                           $n1->id_eleve=$eleve->id;
                            $n1->note=($sNote0/$nbNote0);
                            $n1->save();

                            $sNote0=0;
                            $nbNote0=0;
                        }
                    }
                    $old_idm=$note->id_matiere;
                    $old_coef=$note->coef;
                    $sNote0+=$note->note;
                    $nbNote0++;
                }*/



            foreach ($cm as $m){
                $n_temp0=$n_temp->where('id_matiere',$m->id_matiere);
                $sNote0=0;
                $nbNote0=0;
                foreach ($n_temp0 as $note){
                    if(($m->id_matiere==$note->id_matiere) && ($eleve->id==$note->id_eleve)){
                        $sNote0+=$note->note;
                        $nbNote0++;

                    }

                }
                if($nbNote0>0){
                    $sNote+=($sNote0/$nbNote0)*$m->coef;
                    $sCoef+=$m->coef;
                    $n1=new note();
                    $n1->annee_scolaire=\session('year');
                    $n1->id_matiere=$m->id_matiere;
                    $n1->sequence=$sequence;
                    $n1->id_eleve=$eleve->id;
                    $n1->note=($sNote0/$nbNote0);
                    $n1->save();
                }
            }

            if($sCoef==0)
                $sCoef=1;
            $moy=$sNote/$sCoef;

            //echo $moy.'<br>';

            $nb_moy++;
            $sMoy+=$moy;

            $me=new moyenne_eleve();
            $me->annee_scolaire = \session('year');
            $me->id_eleve = $eleve->id;
            $me->id_classe = $id_classe;
            $me->id_cycle = $id_cycle;
            $me->id_nniveau = $id_nniveau;
            $me->sequence = $sequence;$me->moyenne=$moy;
            $me->save();
            if($moy>$max_moy)
                $max_moy=$moy;
            if($moy<$min_moy)
                $min_moy=$moy;
        }
        $mc=moyenne_classe::firstOrNew(['annee_scolaire'=>session('year'),'id_classe'=>$id_classe, 'id_cycle'=>$id_cycle,'id_nniveau'=>$id_nniveau,'sequence'=>$sequence]);
        $mc->min=$min_moy;
        $mc->max=$max_moy;
        $mc->moyenne=$sMoy/$nb_moy;
        $mc->save();

        $bg=bulletin_genere::firstOrNew(['id_classe'=>$id_classe,  'id_cycle'=>$id_cycle,'id_nniveau'=>$id_nniveau, 'annee_scolaire'=>session('year'),'sequence'=>$sequence]);
        $bg->save();
    }


}

class disc
{
   public $id_eleve;
   public $avertissement;
   public $exclusion;
   public $blame;
   public $retard;
   public $abscence_inj;
   public $abscence_just;

    /**
     * disc constructor.
     * @param $id_eleve
     * @param $avertissement
     * @param $exclusion
     * @param $blame
     * @param $retard
     * @param $abscence_inj
     * @param $abscence_just
     */
    public function __construct($id_eleve, $avertissement, $exclusion, $blame, $retard, $abscence_inj, $abscence_just)
    {
        $this->id_eleve = $id_eleve;
        $this->avertissement = $avertissement;
        $this->exclusion = $exclusion;
        $this->blame = $blame;
        $this->retard = $retard;
        $this->abscence_inj = $abscence_inj;
        $this->abscence_just = $abscence_just;
    }


}
