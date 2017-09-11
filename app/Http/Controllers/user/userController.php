<?php namespace gesecole\Http\Controllers\user;

use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\User;
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
		$values=array('titre'=>trans('admin.liste_utilisateurs'));
		$u=User::where('role','>','1')->orderBy('created_at','desc')->get();
        $values['utilisateurs']=$u;
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

        $u=User::find($request->input('id'));
        $u->active=1;
        $u->role=$request->input('role');
        $u->save();
        session(['success'=>true]);
        return redirect()->action('user\userController@index');
    }

    public function deactivate($id)
    {
        $u=User::find($id);
        $u->active=0;
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
