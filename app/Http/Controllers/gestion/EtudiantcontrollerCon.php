<?php namespace gesecole\Http\Controllers\gestion;

use gesecole\classe;
use gesecole\classeMatiere;
use gesecole\eleve;
use gesecole\Etudiant_user;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;

use gesecole\inscription;
use gesecole\matiere;
use gesecole\NNiveau;
use gesecole\note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class EtudiantcontrollerCon extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    const ELEVE_USER_CONNECTED = 'eleve_user_connected';
    protected $values = [];

    public function index()
    {
        return view('gestion.login', $this->values);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        $user = Etudiant_user::where('username', '=', '' . $username)
            ->where('password', '=', $password)
            ->first();


        if ($user) {

            session([EtudiantcontrollerCon::ELEVE_USER_CONNECTED => $user->id]);
            return redirect('/gestion/login/etudiant/annees');

        } else {

            $error = trans('admin.identifiant');
            session(['eleve_login_error' => $error]);
            return redirect('/gestion/login');
        }

    }

    public function liste_annee()
    {
        if (!EtudiantcontrollerCon::isUserConnected()) {
            $error = trans('admin.errorr');
            session(['eleve_login_error' => $error]);
            return redirect('/gestion/login');

        }
        $user = Etudiant_user::find(session(EtudiantcontrollerCon::ELEVE_USER_CONNECTED));
        $eleve = eleve::find($user->id_eleve);

        $ins = inscription::join('etudiant_users', 'etudiant_users.id_eleve', '=', 'inscription.id_eleve')
            ->where('etudiant_users.id', '=', $user->id)
            ->select('inscription.*')
            ->get();

        $this->values['inscriptions'] = $ins;
        $this->values['utilisateur'] = $eleve;
//            dump($ins[0],$annee);
//            die();
        return view('gestion.index', $this->values);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     *
     */


    public function liste_inscription(Request $request)
    {
        if (!EtudiantcontrollerCon::isUserConnected()) {
            $error = trans('admin.erreur_ecrit');
            session(['eleve_login_error' => $error]);
            return redirect('/gestion/login');

        }
        $this->validate($request, [
            'id_inscription' => 'required',
        ]);

        $id_inscription = $request->input('id_inscription');
        $selected_inscription = inscription::with('classe', 'nniveau', 'cycle')->find($id_inscription);
        $eleve = eleve::find($selected_inscription->id_eleve);


        /*$specialite=inscription::join('etudiant_users', 'etudiant_users.id_eleve', '=', 'inscription.id_eleve')
            ->where('etudiant_users.username','=',$username)
           ->join('classe','classe.id','=','inscription.id_classe')
            ->join('n_niveaus','n_niveaus.id','=','inscription.id_nniveau')
            ->join('cycle','cycle.id','=','inscription.id_cycle')
           ->select('n_niveaus.nom AS nom_niveau','cycle.nom AS nom_cycle','inscription.annee_scolaire','inscription.id_cycle', 'inscription.id_classe', 'inscription.id_nniveau','classe.nom')
          ->where('inscription.id','=',$id_inscription)
            ->get();*/

        /*        $niveau=NNiveau::join('inscription','inscription.id_nniveau','=','n_niveaus.id')
                    ->where('inscription.annee_scolaire','=',$annee)
                    ->where('etudiant_users','etudiant_users.username','=',$username)
                    ->select('n_niveaus.nom')
                    ->get();
                dump($niveau);
                die();
        */

        /*
                $ins2=inscription::join('note', 'inscription.id_eleve', '=', 'note.id_eleve')
                    ->join('matiere', 'matiere.id', '=', 'note.id_matiere')

                    ->join('classe_matiere','classe_matiere.id_matiere','=','matiere.id')
                    ->where('inscription.id','=',$id_inscription)
                    //->where('classe_matiere.id_cycle','=','inscription.id_cycle')
                    //->where('classe_matiere.id_classe','=','inscription.id_classe')
                    //->where('classe_matiere.id_nniveau','=','inscription.id_nniveau')

                    ->select('inscription.annee_scolaire','matiere.nom','inscription.id_cycle', 'inscription.id_classe', 'inscription.id_nniveau','note.note','note.sequence')
                    //->where('etudiant_users.annee_scolaire','=',$annee)
                    ->where('note.annee_scolaire','=',$selected_inscription->annee_scolaire)
                    ->get();*/
        $matieres = matiere::join('classe_matiere', 'matiere.id', '=', 'classe_matiere.id_matiere')
            ->where('classe_matiere.id_classe', '=', $selected_inscription->id_classe)
            ->where('classe_matiere.id_nniveau', '=', $selected_inscription->id_nniveau)
            ->where('classe_matiere.id_cycle', '=', $selected_inscription->id_cycle)
            ->select('matiere.*')
            ->orderBy(App::getLocale() == 'en' ? 'matiere.nom_anglais' : 'matiere.nom')
            ->get();

        $array_id_matiere = $matieres->fetch('id')->toArray();
        $notes = note::where('annee_scolaire', '=', $selected_inscription->annee_scolaire)
            ->where('id_eleve', '=', $selected_inscription->id_eleve)
            ->whereIn('id_matiere', $array_id_matiere)
            ->get();


       $s1=0;
       $s2=0;
       $s3=0;
       $s4=0;
$som_sn2=0;
$som_sn1=0;

       foreach ($matieres as $matiere) {
           $i = 0;
           $i1=0;
           $i2=0;
           $i3=0; $i4=0;

           foreach ($notes as $note) {

               if ($matiere->id == $note->id_matiere) {
                   if ($note->sequence == 1) {
                       $s1 = $note->note;
                       $i++;
                   }

               if ($matiere->id == $note->id_matiere) {
                   if ($note->sequence == 3) {
                       $s2 = $note->note;
                       $i1++;
                   }

               if ($matiere->id == $note->id_matiere) {
                   if ($note->sequence == 2) {
                       $s3 = $note->note;
                       $i2++;
                   }

               if ($matiere->id == $note->id_matiere) {
                   if ($note->sequence == 4) {
                       $s4 = $note->note;
                       $i3++;
                   }
               }
           }}}
                $som_sn1 = round($s1 + $s2)/5;
                $som_sn2 = round($s3 + $s4)/5;
              $i4++;

            }


        }



       /* dump($som_sn1,$som_sn2);
        die();*/





        /*$notes_seq[]= array();


            foreach ($ins2 as $i)
            {
                $notes_seq[$i->nom][$i->sequence] = $i->note;
            }
        unset($notes_seq[0]);
        dump($m,$ins2,$ins2[1],$notes_seq);
        die();*/

        $this->values['selected_inscription']=$selected_inscription;
        $this->values['eleve']=$eleve;
        $this->values['som_sn1']=$som_sn1;
        $this->values['som_sn2']=$som_sn2;
        $this->values['matieres']=$matieres;
        $this->values['notes']=$notes;


        return view('gestion.note',$this->values);


    }


    public function change_password(Request $request){
        $validate_pass=Validator::make($request->all(),
            [
                'ancien_mot_de_passe'=>'required',
                'nouveau_mot_de_passe'=>'required|min:6',
                'password_again'=>'required|min:6',
            ]
        );



        if($validate_pass->fails()){
            $errors=$validate_pass->errors();
            $errors=$errors->all();

            return response()->json($errors);
        }
        $etudiant=Etudiant_user::find(session(EtudiantcontrollerCon::ELEVE_USER_CONNECTED));
        $password=$etudiant->password;
        $apass=$request->input('ancien_mot_de_passe');
        $npass=$request->input('nouveau_mot_de_passe');
        $cpass=$request->input('password_again');

        if (strcmp($npass,$cpass)!=0) {

            return response()->json(['invalid'=>trans('admin.invalide')]);
        }

        if (($apass !=$password)){
            return response()->json(['invalid'=>trans('admin.apass_invalide')]);
        }


        else {
            $etudiant->password=($npass);
            $etudiant->save();
            return response()->json(['success'=>trans('admin.succes')]);
        }
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
    public function update(Request $req)
    {
        $this->validate($req,[
            'id'=>'required|numeric',
        ]);
        $id=$req->input('id');
        $password=$req->input('password');
        $c=Etudiant_user::find($id);
        $c->password=$password;
        $c->save();
        session(['success'=>true]);
        return redirect()->action('admin\EtudiantcontrollerCon@index');
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

    public static function isUserConnected() {
        if(session(EtudiantcontrollerCon::ELEVE_USER_CONNECTED) != null) {
            return true;
        }
        return false;
    }
    public function disconnect_user(){

        Session::forget(EtudiantcontrollerCon::ELEVE_USER_CONNECTED);

        return redirect('/gestion/login');

    }

}

