<?php namespace gesecole\Http\Controllers\eleves;

use gesecole\bulletin_genere;
use gesecole\classe;
use gesecole\classeMatiere;
use gesecole\discipline;
use gesecole\eleve;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\inscription;
use gesecole\moyenne_classe;
use gesecole\moyenne_eleve;
use gesecole\moyenne_matiere;
use gesecole\note;
use gesecole\parametres;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
	    $c=classe::all();
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

    public function regenerate(Request $request){
        $this->validate($request,[
           'classe'=>'required|numeric',
            'sequence'=>'required|numeric'
        ]);
        $id=$request->input('classe');
        $seq=$request->input('sequence');

        $c=classe::all();
        $values['classes']=$c;
        $bg=bulletin_genere::whereAnneeScolaire(\session('year'))->where('id_classe','=',$id)->where('sequence','=',$seq)->get();

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
        $this->validate($request,[
           'sequence'=>'required|numeric',
        ]);
        $values= array('titre'=>'');

        //Initialisations
        $seq=$request->input('sequence');

        $id_e=$request->input('id');
        $id_c=$request->input('id_c');
        if($seq>6){
            return redirect()->to('/eleves/bulletins/print-single-trim/?sequence='.$seq.'&id='.$id_e.'&id_c='.$id_c);
        }

        if($id_c==null){
            $c=DB::select('SELECT c.id,c.nom,c.id_niveau FROM classe c,inscription i WHERE annee_scolaire=? AND i.id_eleve=? AND i.id_classe=c.id ',[session('year'),$id_e]);
            $e=DB::select('SELECT * FROM eleve e WHERE e.id=?',[$id_e]);

        }
        else{
            $c=classe::find($id_c);
            $c[0]=$c;
            $e=DB::select('SELECT * FROM eleve e WHERE e.id In(SELECT id_eleve FROM inscription i ,classe c WHERE annee_scolaire=? AND i.id_classe=c.id AND c.id=?   ) ORDER BY nom,prenom',[session('year'),$c[0]->id]);
        }
        $e1=DB::select('SELECT * FROM eleve e WHERE e.id In(SELECT id_eleve FROM inscription i ,classe c WHERE annee_scolaire=? AND i.id_classe=c.id AND c.id=?   ) ORDER BY nom,prenom',[session('year'),$c[0]->id]);

        $list_e=[];
        $i=0;
        foreach ($e1 as $eleve){
            $list_e[$i]=$eleve->id;
            $i++;
        }
        $notes=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();
        $d=discipline::whereAnneeScolaire(session('year'))->whereIn('id_eleve',$list_e)->get();

        $eff=inscription::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$c[0]->id)
            ->count();

        //Vérifications
        $bg=bulletin_genere::whereAnneeScolaire(session('year'))->where('id_classe','=',$c[0]->id)->where('sequence','=',$seq)->first();
        if($bg==null){
            if(($result=$this->canGenerateBulletin($c[0]->id,$seq))===true){
                $this->generateBulletin($c[0]->id,$seq);
            }else{
                $str='';
                foreach ($result as $m){
                    $str.=((\session('locale')=='en')?$m->nom_anglais:$m->nom). ' ,';
                }
                $err_msg=trans('eleve.cannot_generate').' '.$str;
                session(['error'=>$err_msg]);
                return redirect()->action('eleves\bulletinController@index2');

            }
        }
        //2emes initialisations
        $mm=moyenne_matiere::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$c[0]->id)
            ->where('sequence','=',$seq)
            ->get();
        $me=moyenne_eleve::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$c[0]->id)
            ->where('sequence','=',$seq)
            ->orderBy('moyenne','desc')
            ->get();
        $mc=moyenne_classe::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$c[0]->id)
            ->where('sequence','=',$seq)
            ->get()->first();





        $p=parametres::all();
        $nom_e=$p->where('nom','nom_etablissement',false)->first()->valeur;
        $adresse=$p->where('nom','adresse',false)->first()->valeur;
        $web=$p->where('nom','web',false)->first()->valeur;
        $email=$p->where('nom','email',false)->first()->valeur;
        $numero=$p->where('nom','telephone',false)->first()->valeur;
        $m=DB::select('SELECT m.id,m.nom,m.nom_anglais,cm.coef,m.groupe,cm.max FROM matiere m,classe_matiere cm WHERE cm.id_classe=? AND cm.id_matiere=m.id ORDER BY m.groupe ,m.nom',[$c[0]->id]);



        $values['etablissement']=$nom_e;
        $values['adresse']=$adresse;
        $values['web']=$web;
        $values['notes']=$notes;
        $values['email']=$email;
        $values['numero']=$numero;
        $values['sequence']=$seq;
        $values['classe']=$c[0];
        $values['disciplines']=$d;


        $values['moyenne_matiere']=$mm;
        $values['moyenne_eleve']=$me;
        $values['moyenne_classe']=$mc;



        $values['eleves']=$e;
        $values['matieres']=$m;
        $values['effectif']=$eff;

        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        $values['param']=$p;

        return view('eleves\print_single_seq',$values);
    }

    public function print_single_trim(Request $request)
    {
        $this->validate($request,[
            'sequence'=>'required|numeric',
        ]);
        $values= array('titre'=>'');

        //Initialisations
        $seq=$request->input('sequence');
        $id_e=$request->input('id');
        $id_c=$request->input('id_c');
        if($seq>9){
            return redirect()->to('/eleves/bulletins/print-single-year/?sequence='.$seq.'&id='.$id_e.'&id_c='.$id_c);
        }

        if($seq==7) {
            $seq1 = 1;
            $seq2 = 2;
        }
        elseif ($seq==8){
            $seq1 = 3;
            $seq2 = 4;
        }
        elseif ($seq==9){
            $seq1 = 5;
            $seq2 = 6;
        }

        if($id_c==null){
            $c=DB::select('SELECT c.id,c.nom,c.id_niveau FROM classe c,inscription i WHERE annee_scolaire=? AND i.id_eleve=? AND i.id_classe=c.id ',[session('year'),$id_e]);
            $e=DB::select('SELECT * FROM eleve e WHERE e.id=?',[$id_e]);

        }
        else{
            $c=classe::find($id_c);
            $c[0]=$c;
            $e=DB::select('SELECT * FROM eleve e WHERE e.id In(SELECT id_eleve FROM inscription i ,classe c WHERE annee_scolaire=? AND i.id_classe=c.id AND c.id=?   ) ORDER BY nom,prenom',[session('year'),$c[0]->id]);
        }
        $e1=DB::select('SELECT * FROM eleve e WHERE e.id In(SELECT id_eleve FROM inscription i ,classe c WHERE annee_scolaire=? AND i.id_classe=c.id AND c.id=?   ) ORDER BY nom,prenom',[session('year'),$c[0]->id]);

        $list_e=[];
        $i=0;
        foreach ($e1 as $eleve){
            $list_e[$i]=$eleve->id;
            $i++;
        }
        $notes=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();
        $notesSeq1=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq1)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();
        $notesSeq2=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq2)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();


        $d=discipline::whereAnneeScolaire(session('year'))->whereIn('id_eleve',$list_e)->get();

        $eff=inscription::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$c[0]->id)
            ->count();

        //Vérifications
        $bg=bulletin_genere::whereAnneeScolaire(session('year'))->where('id_classe','=',$c[0]->id)->where('sequence','=',$seq)->first();
        /*if($bg==null){
            if(($result=$this->canGenerateBulletinTrim($c[0]->id,$seq))===true){
                $this->generateBulletinTrim($c[0]->id,$seq);
            }else{
                $str='';
                foreach ($result as $m){
                    $str.=((\session('locale')=='en')?$m->nom_anglais:$m->nom). ' ,';
                }
                $err_msg=trans('eleve.cannot_generate').' '.$str;
                session(['error'=>$err_msg]);
                return redirect()->action('eleves\bulletinController@index2');

            }
        }*/
        if($bg==null){
            if(($result=$this->canGenerateBulletinTrim($c[0]->id,$seq))===true){
                $this->generateBulletinTrim($c[0]->id,$seq);
            }else{
                $str='';
                foreach ($result as $m){
                    $str.='Sequence '.$m.' ,';
                }
                $err_msg=trans('admin.cannot_gen_seq').' '.$str;
                session(['error'=>$err_msg]);
                return redirect()->action('eleves\bulletinController@index2');

            }
        }
        //2emes initialisations
        $mm=moyenne_matiere::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$c[0]->id)
            ->where('sequence','=',$seq)
            ->get();
        $me=moyenne_eleve::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$c[0] ->id)
            ->where('sequence','=',$seq)
            ->orderBy('moyenne','desc')
            ->get();
        $mc=moyenne_classe::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$c[0]->id)
            ->where('sequence','=',$seq)
            ->get()->first();





        $p=parametres::all();
        $nom_e=$p->where('nom','nom_etablissement',false)->first()->valeur;
        $adresse=$p->where('nom','adresse',false)->first()->valeur;
        $web=$p->where('nom','web',false)->first()->valeur;
        $email=$p->where('nom','email',false)->first()->valeur;
        $numero=$p->where('nom','telephone',false)->first()->valeur;
        $m=DB::select('SELECT m.id,m.nom,m.nom_anglais,cm.coef,m.groupe,cm.max FROM matiere m,classe_matiere cm WHERE cm.id_classe=? AND cm.id_matiere=m.id ORDER BY m.groupe ,m.nom',[$c[0]->id]);



        $values['etablissement']=$nom_e;
        $values['adresse']=$adresse;
        $values['web']=$web;

        $values['notes']=$notes;
        $values['seq1']=$seq1;
        $values['seq2']=$seq2;
        $values['notesSeq1']=$notesSeq1;
        $values['notesSeq2']=$notesSeq2;

        $values['email']=$email;
        $values['numero']=$numero;
        $values['sequence']=$seq;
        $values['classe']=$c[0];
        $values['disciplines']=$d;


        $values['moyenne_matiere']=$mm;
        $values['moyenne_eleve']=$me;
        $values['moyenne_classe']=$mc;



        $values['eleves']=$e;
        $values['matieres']=$m;
        $values['effectif']=$eff;

        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        $values['param']=$p;

        return view('eleves\print_single_trim',$values);
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

        if($seq==10) {
            $seq1 = 7;
            $seq2 = 8;
            $seq3 = 9;
        }


        if($id_c==null){
            $c=DB::select('SELECT c.id,c.nom,c.id_niveau FROM classe c,inscription i WHERE annee_scolaire=? AND i.id_eleve=? AND i.id_classe=c.id ',[session('year'),$id_e]);
            $e=DB::select('SELECT * FROM eleve e WHERE e.id=?',[$id_e]);

        }
        else{
            $c=classe::find($id_c);
            $c[0]=$c;
            $e=DB::select('SELECT * FROM eleve e WHERE e.id In(SELECT id_eleve FROM inscription i ,classe c WHERE annee_scolaire=? AND i.id_classe=c.id AND c.id=?   ) ORDER BY nom,prenom',[session('year'),$c[0]->id]);
        }
        $e1=DB::select('SELECT * FROM eleve e WHERE e.id In(SELECT id_eleve FROM inscription i ,classe c WHERE annee_scolaire=? AND i.id_classe=c.id AND c.id=?   ) ORDER BY nom,prenom',[session('year'),$c[0]->id]);

        $list_e=[];
        $i=0;
        foreach ($e1 as $eleve){
            $list_e[$i]=$eleve->id;
            $i++;
        }
        $notes=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();
        $notesSeq1=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq1)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();
        $notesSeq2=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq2)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();
        $notesSeq3=note::whereAnneeScolaire(session('year'))->where('sequence','=',$seq3)->orderBy('note','desc')->whereIn('id_eleve',$list_e)->get();


        $d=discipline::whereAnneeScolaire(session('year'))->whereIn('id_eleve',$list_e)->get();

        $eff=inscription::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$c[0]->id)
            ->count();

        //Vérifications
        $bg=bulletin_genere::whereAnneeScolaire(session('year'))->where('id_classe','=',$c[0]->id)->where('sequence','=',$seq)->first();
        if($bg==null){
            if(($result=$this->canGenerateBulletinAnnuel($c[0]->id,$seq))===true){
                $this->generateBulletinAnnuel($c[0]->id,$seq);
            }else{
                $str='';
                foreach ($result as $m){
                    $str.=trans('eleve.trimestre').' '.$m.' ,';
                }
                $err_msg=trans('admin.cannot_generate').' '.$str;
                session(['error'=>$err_msg]);
                return redirect()->action('eleves\bulletinController@index2');

            }
        }
        //2emes initialisations
        $mm=moyenne_matiere::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$c[0]->id)
            ->where('sequence','=',$seq)
            ->get();
        $me=moyenne_eleve::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$c[0] ->id)
            ->where('sequence','=',$seq)
            ->orderBy('moyenne','desc')
            ->get();
        $mc=moyenne_classe::whereAnneeScolaire(session('year'))
            ->where('id_classe','=',$c[0]->id)
            ->where('sequence','=',$seq)
            ->get()->first();





        $p=parametres::all();
        $nom_e=$p->where('nom','nom_etablissement',false)->first()->valeur;
        $adresse=$p->where('nom','adresse',false)->first()->valeur;
        $web=$p->where('nom','web',false)->first()->valeur;
        $email=$p->where('nom','email',false)->first()->valeur;
        $numero=$p->where('nom','telephone',false)->first()->valeur;
        $m=DB::select('SELECT m.id,m.nom,m.nom_anglais,cm.coef,m.groupe,cm.max FROM matiere m,classe_matiere cm WHERE cm.id_classe=? AND cm.id_matiere=m.id ORDER BY m.groupe ,m.nom',[$c[0]->id]);



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
        $values['classe']=$c[0];
        $values['disciplines']=$d;


        $values['moyenne_matiere']=$mm;
        $values['moyenne_eleve']=$me;
        $values['moyenne_classe']=$mc;



        $values['eleves']=$e;
        $values['matieres']=$m;
        $values['effectif']=$eff;

        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }
        $values['param']=$p;

        return view('eleves\print_single_year',$values);
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

	public function canGenerateBulletin($id_classe,$sequence){
	    $mat=DB::select('SELECT COUNT(DISTINCT n.id_matiere) as nb FROM note n WHERE annee_scolaire=? AND sequence=? AND n.id_eleve IN(SELECT e.id FROM eleve e,inscription i WHERE annee_scolaire=? AND e.id=i.id_eleve AND i.id_classe=?)',[session('year'),$sequence,session('year'),$id_classe]);
	    $pro=classeMatiere::whereIdClasse($id_classe)->get()->count();

	    if($mat['0']->nb<$pro){
            $mats=DB::select('SELECT m.nom,m.nom_anglais FROM matiere m ,classe_matiere cm  WHERE cm.id_classe=? AND cm.id_matiere=m.id AND m.id NOT IN(SELECT DISTINCT n.id_matiere as nb FROM note n WHERE annee_scolaire=? AND sequence=?)',[$id_classe,session('year'),$sequence]);
            return $mats;
        }else
            return true;

    }

    public function canGenerateBulletinTrim($id_classe,$sequence){
	    if($sequence==7) {
            $sequence1 = 1;
            $sequence2 = 2;
        }
        elseif ($sequence==8){
            $sequence1 = 3;
            $sequence2 = 4;
        }
        elseif ($sequence==9){
            $sequence1 = 5;
            $sequence2 = 6;
        }

        /*$mat=DB::select('SELECT COUNT(DISTINCT n.id_matiere) as nb FROM note n WHERE annee_scolaire=? AND (sequence=? OR sequence=?) AND n.id_eleve IN(SELECT e.id FROM eleve e,inscription i WHERE annee_scolaire=? AND e.id=i.id_eleve AND i.id_classe=?)',[session('year'),$sequence1,$sequence2,session('year'),$id_classe]);
        $pro=classeMatiere::whereIdClasse($id_classe)->get()->count();

        if($mat['0']->nb<$pro){
            $mats=DB::select('SELECT m.nom,m.nom_anglais FROM matiere m ,classe_matiere cm  WHERE cm.id_classe=? AND cm.id_matiere=m.id AND m.id NOT IN(SELECT DISTINCT n.id_matiere as nb FROM note n WHERE annee_scolaire=? AND (sequence=? OR sequence=?)',[$id_classe,session('year'),$sequence1,$sequence2]);
            return $mats;
        }else
            return true;*/

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


    }

    public function canGenerateBulletinAnnuel($id_classe,$sequence){
        if($sequence==10) {
            $sequence1 = 7;
            $sequence2 = 8;
            $sequence3 = 9;
        }


        $bg1=bulletin_genere::whereAnneeScolaire(session('year'))->where('id_classe','=',$id_classe)->where('sequence','=',$sequence1)->first();
        $bg2=bulletin_genere::whereAnneeScolaire(session('year'))->where('id_classe','=',$id_classe)->where('sequence','=',$sequence2)->first();
        $bg3=bulletin_genere::whereAnneeScolaire(session('year'))->where('id_classe','=',$id_classe)->where('sequence','=',$sequence3)->first();


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

	public function generateBulletin($id_classe,$sequence){
	    $cm=classeMatiere::whereIdClasse($id_classe)->get();
        $e=DB::select('SELECT e.id,e.nom,e.prenom FROM eleve e,inscription i WHERE annee_scolaire=? AND i.id_classe=? AND e.id=i.id_eleve',[session('year'),$id_classe] );
        $n=DB::select('SELECT * FROM note n WHERE annee_scolaire=?  AND sequence=? AND n.id_eleve IN(SELECT e.id FROM eleve e,inscription i WHERE annee_scolaire=? AND i.id_classe=? AND e.id=i.id_eleve)',[session('year'),$sequence,session('year'),$id_classe] );
        //moyenne matiere

        foreach ($cm as $m){

            $nbNote=0;
            $snote=0;
            $min=1000000;
            $max=0;

            foreach ($n as $note){

                if($m->id_matiere==$note->id_matiere){
                    if($note->note>$max)
                        $max=$note->note;
                    if($note->note<$min)
                        $min=$note->note;

                    $nbNote++;
                    $snote+=$note->note;
                }

            }
            $mm=moyenne_matiere::firstOrNew(['annee_scolaire'=>session('year'),'id_matiere'=>$m->id_matiere,'id_classe'=>$m->id_classe,'sequence'=>$sequence]);
            $mm->min=$min;
            $mm->id_classe=$id_classe;
            $mm->id_matiere=$m->id_matiere;
            $mm->max=$max;
            $mm->sequence=$sequence;
            $mm->annee_scolaire=session('year');
            $mm->moyenne=($snote/$nbNote);
            $mm->save();
        }

        //moyenne eleve
        $min_moy=1000000;
        $max_moy=0;
        $nb_moy=0;
        $sMoy=0;

        foreach ($e as $eleve){
            $sNote=0;
            $sCoef=0;
            foreach ($cm as $m){
                foreach ($n as $note){
                    if(($m->id_matiere==$note->id_matiere) && ($eleve->id==$note->id_eleve)){
                        $sNote+=$note->note*$m->coef;
                        $sCoef+=$m->coef;
                    }
                }
            }
           $me=moyenne_eleve::firstOrNew(['annee_scolaire'=>session('year'),'id_eleve'=>$eleve->id,'sequence'=>$sequence,'id_classe'=>$id_classe]);

            $moy=$sNote/$sCoef;

            $nb_moy++;
            $sMoy+=$moy;

            $me->moyenne=$moy;
            $me->save();
            if($moy>$max_moy)
                $max_moy=$moy;
            if($moy<$min_moy)
                $min_moy=$moy;
        }
        $mc=moyenne_classe::firstOrNew(['annee_scolaire'=>session('year'),'id_classe'=>$id_classe,'sequence'=>$sequence]);
        $mc->min=$min_moy;
        $mc->max=$max_moy;
        $mc->moyenne=$sMoy/$nb_moy;
        $mc->save();

        $bg=bulletin_genere::firstOrNew(['id_classe'=>$id_classe,'annee_scolaire'=>session('year'),'sequence'=>$sequence]);
        $bg->save();
    }

    public function generateBulletinTrim($id_classe,$sequence){
        if($sequence==7) {
            $sequence1 = 1;
            $sequence2 = 2;
        }
        elseif ($sequence==8){
            $sequence1 = 3;
            $sequence2 = 4;
        }
        elseif ($sequence==9){
            $sequence1 = 5;
            $sequence2 = 6;
        }
        $cm=classeMatiere::whereIdClasse($id_classe)->get();
        $e=DB::select('SELECT e.id,e.nom,e.prenom FROM eleve e,inscription i WHERE annee_scolaire=? AND i.id_classe=? AND e.id=i.id_eleve',[session('year'),$id_classe] );
        $n=DB::select('SELECT * FROM note n WHERE annee_scolaire=?  AND (sequence=? OR sequence=?) AND n.id_eleve IN(SELECT e.id FROM eleve e,inscription i WHERE annee_scolaire=? AND i.id_classe=? AND e.id=i.id_eleve)',[session('year'),$sequence1,$sequence2,session('year'),$id_classe] );

        //moyenne matiere

        foreach ($cm as $m){

            $nbNote=0;
            $snote=0;
            $min=1000000;
            $max=0;

            foreach ($e as $eleve){
                $sNote0=0;
                $nbNote0=0;
                foreach ($n as $note){

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


            $mm=moyenne_matiere::firstOrNew(['annee_scolaire'=>session('year'),'id_matiere'=>$m->id_matiere,'id_classe'=>$m->id_classe,'sequence'=>$sequence]);
            $mm->min=$min;
            $mm->id_classe=$id_classe;
            $mm->id_matiere=$m->id_matiere;
            $mm->max=$max;
            $mm->sequence=$sequence;
            $mm->annee_scolaire=session('year');
            $mm->moyenne=($snote/$nbNote);
            $mm->save();
        }

        //moyenne eleve
        $min_moy=1000000;
        $max_moy=0;
        $nb_moy=0;
        $sMoy=0;
        foreach ($e as $eleve){
            $sNote=0;
            $sCoef=0;
            foreach ($cm as $m){
                $sNote0=0;
                $nbNote0=0;
                foreach ($n as $note){
                    if(($m->id_matiere==$note->id_matiere) && ($eleve->id==$note->id_eleve)){
                        $sNote0+=$note->note;
                        $nbNote0++;

                    }

                }
                if($nbNote0>0){
                    $sNote+=($sNote0/$nbNote0)*$m->coef;
                    $sCoef+=$m->coef;

                    $n1=note::firstOrNew(['annee_scolaire'=>session('year'),'id_matiere'=>$m->id_matiere,'sequence'=>$sequence,'id_eleve'=>$eleve->id]);
                    $n1->note=($sNote0/$nbNote0);
                    $n1->save();
                }
            }
            $me=moyenne_eleve::firstOrNew(['annee_scolaire'=>session('year'),'id_eleve'=>$eleve->id,'sequence'=>$sequence,'id_classe'=>$id_classe]);

            $moy=$sNote/$sCoef;

            $nb_moy++;
            $sMoy+=$moy;

            $me->moyenne=$moy;
            $me->save();
            if($moy>$max_moy)
                $max_moy=$moy;
            if($moy<$min_moy)
                $min_moy=$moy;
        }
        $mc=moyenne_classe::firstOrNew(['annee_scolaire'=>session('year'),'id_classe'=>$id_classe,'sequence'=>$sequence]);
        $mc->min=$min_moy;
        $mc->max=$max_moy;
        $mc->moyenne=$sMoy/$nb_moy;
        $mc->save();

        $bg=bulletin_genere::firstOrNew(['id_classe'=>$id_classe,'annee_scolaire'=>session('year'),'sequence'=>$sequence]);
        $bg->save();
    }

    public function generateBulletinAnnuel($id_classe,$sequence){

        if($sequence==10) {
            $sequence1 = 7;
            $sequence2 = 8;
            $sequence3 = 9;
        }

        $cm=classeMatiere::whereIdClasse($id_classe)->get();
        $e=DB::select('SELECT e.id,e.nom,e.prenom FROM eleve e,inscription i WHERE annee_scolaire=? AND i.id_classe=? AND e.id=i.id_eleve',[session('year'),$id_classe] );
        $n=DB::select('SELECT * FROM note n WHERE annee_scolaire=?  AND (sequence=? OR sequence=? OR sequence=?) AND n.id_eleve IN(SELECT e.id FROM eleve e,inscription i WHERE annee_scolaire=? AND i.id_classe=? AND e.id=i.id_eleve)',[session('year'),$sequence1,$sequence2,$sequence3,session('year'),$id_classe] );

        //moyenne matiere

        foreach ($cm as $m){

            $nbNote=0;
            $snote=0;
            $min=1000000;
            $max=0;

            foreach ($e as $eleve){
                $sNote0=0;
                $nbNote0=0;
                foreach ($n as $note){

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


            $mm=moyenne_matiere::firstOrNew(['annee_scolaire'=>session('year'),'id_matiere'=>$m->id_matiere,'id_classe'=>$m->id_classe,'sequence'=>$sequence]);
            $mm->min=$min;
            $mm->id_classe=$id_classe;
            $mm->id_matiere=$m->id_matiere;
            $mm->max=$max;
            $mm->sequence=$sequence;
            $mm->annee_scolaire=session('year');
            $mm->moyenne=($snote/$nbNote);
            $mm->save();
        }

        //moyenne eleve
        $min_moy=1000000;
        $max_moy=0;
        $nb_moy=0;
        $sMoy=0;
        foreach ($e as $eleve){
            $sNote=0;
            $sCoef=0;
            foreach ($cm as $m){
                $sNote0=0;
                $nbNote0=0;
                foreach ($n as $note){
                    if(($m->id_matiere==$note->id_matiere) && ($eleve->id==$note->id_eleve)){
                        $sNote0+=$note->note;
                        $nbNote0++;

                    }

                }
                if($nbNote0>0){
                    $sNote+=($sNote0/$nbNote0)*$m->coef;
                    $sCoef+=$m->coef;

                    $n1=note::firstOrNew(['annee_scolaire'=>session('year'),'id_matiere'=>$m->id_matiere,'sequence'=>$sequence,'id_eleve'=>$eleve->id]);
                    $n1->note=($sNote0/$nbNote0);
                    $n1->save();
                }
            }
            $me=moyenne_eleve::firstOrNew(['annee_scolaire'=>session('year'),'id_eleve'=>$eleve->id,'sequence'=>$sequence,'id_classe'=>$id_classe]);

            $moy=$sNote/$sCoef;

            $nb_moy++;
            $sMoy+=$moy;

            $me->moyenne=$moy;
            $me->save();
            if($moy>$max_moy)
                $max_moy=$moy;
            if($moy<$min_moy)
                $min_moy=$moy;
        }
        $mc=moyenne_classe::firstOrNew(['annee_scolaire'=>session('year'),'id_classe'=>$id_classe,'sequence'=>$sequence]);
        $mc->min=$min_moy;
        $mc->max=$max_moy;
        $mc->moyenne=$sMoy/$nb_moy;
        $mc->save();

        $bg=bulletin_genere::firstOrNew(['id_classe'=>$id_classe,'annee_scolaire'=>session('year'),'sequence'=>$sequence]);
        $bg->save();
    }


}
