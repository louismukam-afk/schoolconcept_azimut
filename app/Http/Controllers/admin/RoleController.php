<?php namespace gesecole\Http\Controllers\admin;

use gesecole\Functions;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;

use gesecole\Role;
use gesecole\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller {

    public function __construct()
    {
        session(['big_title'=>trans('admin.dashboard')]);

    }

    public function index($id)
    {
        $u=User::find($id);
        $titre='Roles: '.$u->fullname;
        $r=Role::whereIdUser($id)->get();

        $ro=\Auth::user()->roles->fetch('value')->toArray();
        $valid=Functions::pp_exists($ro,0);

        $values= array('titre'=>$titre,'roles'=>$r,'validated'=>$valid,'user'=>$u);
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }

        return view('admin\roles',$values);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'id'=>'required',
            'role'=>'required',
        ]);
        $id=$request->input('id');
        $role=$request->input('role');

        $r=Role::whereIdUser($id)->where('value','=',$role)->first();
        if($r)
            return redirect(\URL::previous());

        $c=new Role();
        $c->id_user=$id;
        $c->value=$role;
        $c->save();

        session(['success'=>true]);
        return redirect()->to(\URL::previous())->with('success',true);
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
        $c=Role::find($id);
        $c->delete();

        return redirect()->to(\URL::previous())->with('success',true);
    }

}
