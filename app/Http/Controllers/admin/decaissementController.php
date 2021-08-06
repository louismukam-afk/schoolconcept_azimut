<?php namespace gesecole\Http\Controllers\admin;

use gesecole\decaissement;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;

use gesecole\personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use gesecole\parametres;
use Illuminate\Support\Facades\URL;

class decaissementController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function __construct()
    {
        session(['big_title'=>trans('admin.comptabilite')]);
        $this->renewSchoolYear();
    }

	public function index(Request $req)
	{
        $values= array('titre'=>trans('admin.sortie_caisse'));

        if(session('success')){

            $values['success']=session('success');
            Session::forget('success');
        }
        $pattern=$req->input('search');
            $d=decaissement::with('personnel')
                ->join('personnel', 'id_personnel', '=', 'personnel.id')
                ->whereRaw(' lower(nom) LIKE ?', ['%' . strtolower($pattern) . '%'])
                ->where('annee_scolaire','=',\session('year'))
                ->select(['decaissement.*', 'personnel.nom as nom_personnel'])
                ->orderBy('created_at','desc')
                ->paginate(30);



        $p=personnel::orderBy('nom')->get();
        $values['personnel']=$p;
        $values['decaissement']=$d;

        return view('admin/search_decaissement',$values);
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
		   'personnel'=>'required|numeric',
            'montant'=>'required|numeric',
            'motif'=>'required',
        ]);
		$d=new decaissement();
		$d->id_personnel=$request->input('personnel');
        $d->montant=$request->input('montant');
        $d->motif=$request->input('motif');
        $d->date=date('Y-m-d');
        $d->annee_scolaire=\session('year');

        $current_user = \Auth::user();
        $d->id_creator = $current_user->id;
        $d->id_last_editor = $current_user->id;
        $d->save();

        session(['success'=>true]);
        return redirect()->action('admin\decaissementController@index');
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
	public function update_amount(Request $request)
	{
		$this->validate($request, [
		    'id' => 'required|numeric',
		    'montant' => 'required|numeric',
        ]);
		$id = $request->input('id');
		$montant = $request->input('montant');
		$d = decaissement::find($id);
		if($d) {
		    $d->montant = $montant;
            $current_user = \Auth::user();
            $d->id_last_editor = $current_user->id;
		    $d->save();
        }
        session(['success'=>true]);
		return redirect()->to(URL::previous());
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
