<?php namespace gesecole\Http\Controllers\personnel;

use gesecole\dispense;
use gesecole\heure_realise;
use gesecole\horaire;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\parametres;
use Illuminate\Support\Facades\Session;
use gesecole\matiere;
use gesecole\personnel;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class presenceController extends Controller {

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
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$titre=trans('admin.search_personnel');
		$values['titre']=$titre;
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }
		return view('personnel\search_personnel_presence',$values);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{

        $p=personnel::find($id);
        $h=heure_realise::firstOrNew(['id_personnel'=>$id]);
        $values=array('titre'=>trans('admin.compte_heure_de').' '.$p->nom);
		$values['personnel']=$p;
        $values['realise']=$h;


        return view('personnel\updatePresence',$values);
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
	public function update(Request $request,$id)
	{
        $this->validate($request,[
            'nombre'=>'numeric|required',
        ]);
        $nombre=$request->input('nombre');
        $h=heure_realise::firstOrNew(['id_personnel'=>$id]);
        $h->nombre+=$nombre;
        $h->save();
        session(['success'=>true]);
        return redirect()->action('personnel\presenceController@index');
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
