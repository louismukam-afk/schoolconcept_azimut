<?php namespace gesecole\Http\Controllers;
use Illuminate\Http\Request;
use gesecole\parametres;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
    public function __construct()
    {

        session(['big_title'=>trans('menu.accueil')]);
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

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('index');
	}

	public function editYear(Request $req){
        $this->validate($req,[
            'first_year'=>'required|numeric|min:2017',
            'second_year'=>'required|numeric|min:2018',
        ]);
        $fy=$req->input('first_year');
        $sy=$req->input('second_year');
        $school_year=$fy.'-'.$sy;
        $p=parametres::whereNom('annee_scolaire')->get()->first();
        $p->valeur=$school_year;
        $p->save();
        session(['year'=>$p->valeur]);
        return redirect()->to('/');
    }
    public function blk(){
	    $p=parametres::firstOrNew(['nom'=>'auth']);
	    $p->valeur='OK';
	    $p->save();
	    return redirect()->action('WelcomeController@index');
    }

    public function unblk(){
        $p=parametres::whereNom('auth')->first();
        $p->delete();
        return redirect()->action('WelcomeController@index');
    }

}
