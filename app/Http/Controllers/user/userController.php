<?php namespace gesecole\Http\Controllers\user;

use gesecole\Action;
use gesecole\Functions;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\parametres;
use gesecole\personnel;
use gesecole\Role;
use gesecole\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class userController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function __construct()
    {
        session(['big_title'=>trans('admin.dashboard')]);
        $this->renewSchoolYear();
    }

	public function index()
	{
        $values=array('titre'=>trans('admin.liste_utilisateurs'));
        $current_user = Auth::user();
        if(!$current_user->isCreator()) {
            $u_array=Action::whereRaw('action_name = ? OR action_name = ?', [Action::ACTION_ALL, Action::ACTION_ALMOST_ALL])->get(['id_user'])->fetch('id_user')->toArray();
            array_push($u_array, $current_user->id);
            $u=User::whereNotIn('id',$u_array)->orderBy('created_at','desc')->paginate(30);
        }
        else {
            $u_array=Action::whereRaw('action_name = ?', [Action::ACTION_ALL])->get(['id_user'])->fetch('id_user')->toArray();
            array_push($u_array, $current_user->id);
            $u=User::whereNotIn('id',$u_array)->orderBy('created_at','desc')->paginate(30);

        }
        /*if(!Functions::contain(\Auth::user()->roles->fetch('value')->toArray(),0)){
            $u_array=Role::where('value','<=',1)->get()->fetch('id_user')->toArray();
            array_push($u_array, $current_user->id);
            $u=User::whereNotIn('id',$u_array)->orderBy('created_at','desc')->paginate(30);
        }
        else{
            $u_array=Role::where('value','<=',0)->get()->fetch('id_user')->toArray();
            array_push($u_array, $current_user->id);
            $u=User::whereNotIn('id',$u_array)->orderBy('created_at','desc')->paginate(30);

        }*/

        $p=personnel::orderBy('nom')->get();

        $values['utilisateurs']=$u;
        $values['personnels']=$p;
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }
        return view('admin\utilisateurs',$values);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
    public function activate(Request $request)
    {

        $this->validate($request,[
           'id'=>'required|numeric',
            'personnel'=>'required|numeric',
        ]);
        $u=User::find($request->input('id'));
        $u->active=1;
        $personnel=$request->input('personnel');
        if($personnel!=0){
            $u->id_personnel=$personnel;
        }
        $u->save();
        session(['success'=>true]);
        return redirect()->action('user\userController@index');
    }

    public function deactivate($id)
    {
        $u=User::find($id);
        $u->active=0;
        $u->id_personnel=0;
        $u->save();
        session(['success'=>true]);

        return redirect()->action('user\userController@index');


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

}
