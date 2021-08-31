<?php namespace gesecole\Http\Controllers\inscriptions;

use gesecole\Frais;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\NNiveau;
use gesecole\payement;
use gesecole\parametres;
use gesecole\cycle;
use gesecole\niveau;
use gesecole\classe;
use gesecole\scolarite;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use gesecole\eleve;
use gesecole\inscription;
use Illuminate\Support\Facades\Session;

class payementController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     *
     */
    public function __construct()
    {

        session(['big_title'=>trans('menu.gestion_inscriptions')]);
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
        $values= array('titre'=>trans('inscription.get_payement'));
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }

        return view('inscriptions/search_deposer',$values);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function search(Request $req)
    {
        $pattern=$req->input('search');
        $p=payement::whereNumero($pattern)->first();
        if($p==null) {
            $e = DB::table('eleve')->whereRaw('lower(nom) LIKE ?',['%'.strtolower($pattern).'%'])
                ->orWhereRaw('lower(prenom) LIKE ?',['%'.strtolower($pattern).'%'])
                ->orWhereRaw('lower(matricule) LIKE ?',['%'.strtolower($pattern).'%'])
                ->orderBy('nom')->orderBy('prenom')
                ->paginate(30);

            $values = array('titre' => trans('inscription.get_payement'));
            $values['eleves'] = $e;
            session(['search_result' => true]);
        }
        else{
            $values = array('titre' => trans('inscription.info_payement'));
            $values['payement'] = $p;
            $e=eleve::find($p->id_eleve);
            $values['eleve'] = $e;
            session(['info_payement' => true]);

        }
        return view('inscriptions/search_deposer',$values);
    }

    public function create($id_eleve)
    {
        $e=eleve::find($id_eleve);
        $f=Frais::orderBy('nom')->get();
        $values= array('titre'=>trans('inscription.get_payement'));
        $values['e']=$e;
        $values['frais']=$f;
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }
        if(session('custom_error')){
            $values['custom_error']=session('custom_error');
            Session::forget('custom_error');

        }

        return view('inscriptions/add_payement',$values);
    }

    public function index_liste($id_eleve){

        $values= array('titre'=>trans('inscription.liste_paiement'));
        $p=payement::whereIdEleve($id_eleve)->where('annee_scolaire','=',session('year'))->orderBy('date_payement')->get();
        $values['payements']=$p;
        $e=eleve::find($id_eleve);
        $name=$e->nom.' '.$e->prenom;
        $values['name']=$name;
        $values['id_eleve']=$id_eleve;
        if(session('success')){
            $values['success']=session('success');
            Session::forget('success');
        }

        return view('inscriptions/liste_paiement',$values);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $req)
    {
        $this->validate($req,[
            'id_eleve'=>'required|numeric',
            'montant'=>'required|numeric',
            'type'=>'required',
            'date'=>'required'

        ]) ;
        $id_eleve=$req->input('id_eleve');
        $montant=$req->input('montant');
        $mode_p=$req->input('mode_paye');
        $ref_ver=$req->input('ref_versement');
        $lettre=$req->input('lettre');
        $type=$req->input('type');
       $date=$req->input('date_payement');
        if($type<>0 && $type<>-1){
            $f=Frais::find($type);
            $motif=$f->nom;
        }
        else{
            if($type==-1)
                $motif='Inscription';
            else
                $motif=trans('admin.schoolfees');
        }


        if($req->input('motif'))
            $motif.=' - '.$req->input('motif');

        $annee_scolaire=session('year');

        //$today=date('Y-m-d');

        $test=inscription::whereRaw("id_eleve=? and annee_scolaire=?",[$id_eleve,$annee_scolaire])->get()->first();

        if($test==null){
            session(['custom_error'=>trans('inscription.pas_inscrit')]);
            return redirect()->action('inscriptions\payementController@create',['id_eleve'=>$id_eleve]);
        }
        $today=date('Y-m-d');

        $test=inscription::whereRaw("id_eleve=? and annee_scolaire=?",[$id_eleve,$annee_scolaire])->get()->first();

        if($test==null){
            session(['custom_error'=>trans('inscription.pas_inscrit')]);
            return redirect()->action('inscriptions\payementController@create',['id_eleve'=>$id_eleve]);
        }
        $p=new payement();
        $p->annee_scolaire=$annee_scolaire;
        $p->motif=$motif;
        $p->montant=$montant;
        $p->id_eleve=$id_eleve;
        $p->date_payement=$today;
        $p->mode_paye=$mode_p;
        $p->ref_versement=$ref_ver;
        $p->lettre=$lettre;
        $p->type=$type;
        if($date)
            $p->date_payement=$date;
        else
            $p->date_payement=$today;

        $p->save();
        $numero=sprintf('%08d',$p->id);
        $p->numero=$numero;
        $p->save();

        session(['success'=>$p->id]);
        return redirect()->action('inscriptions\payementController@index_liste',[$id_eleve]) ;
        // return redirect()->action('inscriptions\payementController@index') ;
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

    public function print_receipe($id){
        $p=payement::find($id);
        if($p->type<>0){
            $f=Frais::find($p->type);
            $values['frais']=$f;

        }
       // $i=inscription::whereAnneeScolaire(session('year'))->where('id_eleve',$p->id_eleve,false)->first();
        $i=inscription::whereAnneeScolaire(session('year'))->where('id_eleve',$p->id_eleve,false)->first();
        $cycle=cycle::find($i->id_cycle);
        $cy=$cycle->nom;
        $id_cycle=$cycle->id;

        $c=classe::find($i->id_classe);
        $n=niveau::find($c->id_niveau);
        $nn=NNiveau::find($i->id_nniveau);
        //$sc=$i->classe->niveau->scolarite->where('id_cycle','=',$id_cycle)->first();
        $sc=scolarite::whereIdNiveau($c->id_niveau)->where('id_cycle','=',$id_cycle)->first();
//        dump($sc);
//        die();
        $ps=payement::whereIdEleve($p->id_eleve)->where('annee_scolaire','=',session('year'))->where('id_inscription','=',0)
            ->where('type','=',0)->get();

      /*  if($sc==null)
            $total=0;
        else
            $total=$sc->tranche1+$sc->tranche2+$sc->tranche3+$sc->tranche4+$sc->tranche5;

       // $ps=payement::whereIdEleve($p->id_eleve)->where('annee_scolaire','=',session('year'))->where('motif','<>','Inscription')->get();

        $solde=0;
        foreach ($ps as $pay){
            $solde+=$pay->montant;
        }*/
        if($p->type<>0){
            $fp=payement::whereIdEleve($p->id_eleve)->where('annee_scolaire','=',session('year'))
                ->where('type','=',$p->type)->get();
            if($fp)
                $solde=$fp->sum('montant');
            else
                $solde=0;

            $total=$f->montant;

        }
        else {
            if ($sc)
                $total = $sc->tranche1 + $sc->tranche2 + $sc->tranche3 + $sc->tranche4 + $sc->tranche5;
            else
                $total = 0;
            $solde = 0;
            foreach ($ps as $pay) {
                $solde += $pay->montant;
            }
        }
        $pa=parametres::all();
        $pa=$pa->where('nom','nom_etablissement',false)->first();
        $nom_e=$pa->valeur;
        $values['cycle']=$cy;
        $values['classe']=$c;
        $values['niveau']=$n;
        $values['nniveau']=$nn;
        $values['payement']=$p;
        $values['inscription']=$i;
        $values['nom_etablissement']=$nom_e;
        $values['total']=$total;
        $values['solde']=$solde;

        return view('inscriptions/imprimer_recu_p',$values);

    }

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

}
