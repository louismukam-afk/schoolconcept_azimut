<?php namespace gesecole\Http\Controllers\admin;

use gesecole\cycle;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\NNiveau;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use gesecole\parametres;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class nniveauController extends Controller {

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
        $titre=trans('admin.nniveau');
        $nn=NNiveau::orderBy('ordre')->paginate(20);
        $cy=cycle::all();
        $values= array('titre'=>$titre,'nniveaux'=>$nn,'cycles'=>$cy);
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }

        return view('admin/nniveaux',$values);
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
		   'nom'=>'required',
            'cycle'=>'required|numeric',
            'ordre'=>'required|numeric',

        ]);
        $nn= new NNiveau();
        $nn->nom=$request->input('nom');
        $nn->id_cycle=$request->input('cycle');
        $nn->ordre = $request->input('ordre');
        $nn->save();
        session(['success'=>true]);
        return redirect()->action('admin\nniveauController@index');
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
	public function update(Request $request)
	{
        $this->validate($request,[
            'id'=>'required|numeric',
            'nom'=>'required',
            'cycle'=>'required|numeric',
            'ordre'=>'required|numeric',

        ]);
        $nn= NNiveau::find($request->input('id'));
        $nn->nom=$request->input('nom');
        $nn->id_cycle=$request->input('cycle');
        $nn->ordre = $request->input('ordre');
        $nn->save();
        session(['success'=>true]);
        return redirect()->action('admin\nniveauController@index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        try {
            NNiveau::destroy($id);
            session(['success'=>true]);
        } catch (QueryException $fe) {
            return  redirect()->to(URL::previous())->withErrors(['constraint_violation' => trans('admin.cannot_delete')]);
        }

        return redirect()->to(URL::previous());
	}

	public function get($id){
	    $nn=NNiveau::where('id_cycle','=',$id)->orderBy('ordre')->get();
	    $nn=$nn->toArray();
	    return response()->json($nn);
    }



}
