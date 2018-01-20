<?php namespace gesecole\Http\Controllers;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use gesecole\parametres;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Psy\Util\Json;

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

      // $c=new Encrypter(config('env.key'));
       //dump($c->encrypt('schoooncept'));
        return view('home',['isHome'=>true]);
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
    public function fr_locale(){
        $prevUrl=\URL::previous();
        session(['locale'=>'fr']);
        return redirect()->to($prevUrl) ;
    }
    public function en_locale(){
        $prevUrl=\URL::previous();
        session(['locale'=>'en']);
        return redirect()->to($prevUrl) ;
    }
    public function change_password(Request $request){
        $validate_pass=Validator::make($request->all(),
            [
                'ancien_mot_de_passe'=>'required',
                'nouveau_mot_de_passe'=>'required|min:6',
                'password_again'=>'required|min:6|same:nouveau_mot_de_passe',
            ]
            );



        if($validate_pass->fails()){
            $errors=$validate_pass->errors();
            $errors=$errors->all();

           return response()->json($errors);
        }
        $password=\Auth::user()->password;
        $tpass=$request->input('ancien_mot_de_passe');

        if (!\Hash::check($tpass,$password)){
            return response()->json(['invalid'=>trans('admin.apass_invalide')]);
        }
        else {
            \Auth::user()->password=bcrypt($request->input('nouveau_mot_de_passe'));
            \Auth::user()->save();
            return response()->json(['success'=>trans('admin.succes')]);
        }
    }
}
