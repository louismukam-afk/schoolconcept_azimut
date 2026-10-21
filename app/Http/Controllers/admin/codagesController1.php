<?php namespace gesecole\Http\Controllers\admin;

use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;

use Illuminate\Http\Request;

class codagesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

  

	public function index()
	{
		 $titre=trans('admin.code');
        $nn=codages::orderBy('nom')->get();
        $values= array('titre'=>$titre,'codes'=>$nn);
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }

        return view('admin/codages',$values);
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
        ]);
        $nn= new codages();
        $nn->nom=$request->input('nom');
        $nn->save();
        session(['success'=>true]);
    return redirect()->action('admin\codagesController@index');
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
        ]);
        $nn = codages::find($request->input('id'));
        $nn->nom=$request->input('nom');
        $nn->save();
        session(['success'=>true]);
        return redirect()->action('admin\codagesController@index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        codages::destroy($id);
        session(['success'=>true]);
        return redirect()->action('admin\codagesController@index');
	}


}
