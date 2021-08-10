<?php namespace gesecole\Http\Controllers\gestion;

use gesecole\eleve;
use gesecole\Etudiant_user;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;

use gesecole\inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EtudiantcontrollerCon extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    protected $values=[];

    public function index()
    {
        $this->values['erreur']='';
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
        $this->validate($request,[
            'username'=>'required',
            'password'=>'required',
        ]);

        $username=$request->input('username');
        $password=$request->input('password');

        $user=Etudiant_user::where('username','=',''.$username)
            ->where( 'password','=',$password)
        ;



        $count=count($user);
        if ($count==1)
        {

            $eleve=eleve::where('matricule','=',$username)->get();

            $ins=inscription::join('etudiant_users', 'etudiant_users.id_inscription', '=', 'inscription.id')
                ->where('etudiant_users.username','=',$username)
                ->join('note', 'etudiant_users.id_eleve', '=', 'note.id_eleve')
                ->join('matiere', 'matiere.id', '=', 'note.id_matiere')

                ->select('inscription.annee_scolaire', 'matiere.nom','inscription.id_cycle', 'inscription.id_classe', 'inscription.id_nniveau','note.note','note.sequence')
                ->get();

            $annee=inscription::join('etudiant_users', 'etudiant_users.id_inscription', '=', 'inscription.id')
                ->where('etudiant_users.username','=',$username)


                ->select('inscription.annee_scolaire')
                ->get();


            /*dump($ins[10],$ins[17]);
            die();*/

            /*foreach ($ins as $i)
            {
                $m[''.$i['annee_scolaire']] =DB::select('SELECT m.* FROM matiere m ,classe_matiere cm WHERE m.id=cm.id_matiere AND cm.id_classe=? AND cm.id_cycle=? AND cm.id_nniveau=? ORDER BY m.nom',[$i['id_classe'],$i['id_cycle'],$i['id_nniveau']]);

            }*/
            /*$ij=0; $j=0;

            foreach ($ins as $i)
            {
                foreach ($m as $key => $value){

                    if ($i['annee_scolaire'] == $key){
                        $ins[$ij]['matieres']=$value;
                    }
                }
                $ij++;
            }

*/





            $this->values['inscriptions']=$ins;
            $this->values['utilisateur']=$eleve[0];
            $this->values['annee']=$annee;
//            dump($ins[0],$annee);
//            die();
            return view('gestion.index',$this->values);
        }
        else{
            $error='Veuillez entrer les identifiants correctes';
            $this->values['erreur']=$error;
            return view('gestion.login',$this->values);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function liste_inscrip($username)
    {

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

}
