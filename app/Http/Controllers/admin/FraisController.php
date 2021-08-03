<?php namespace gesecole\Http\Controllers\admin;

use gesecole\Frais;
use gesecole\Http\Controllers\Controller;

use gesecole\parametres;
use gesecole\payement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class FraisController extends Controller {

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
        $n=Frais::orderBy('nom')->paginate(50);
        $values= array('titre'=>trans('admin.frais_l'),'frais'=>$n);
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }
        if(session('custom_error')){
            $values['custom_error']=session('custom_error');
            Session::forget('custom_error');

        }
        return view('admin.frais',$values);
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
            'code'=>'required',
            'nom'=>'required',
            'montant'=>'required|numeric',
        ]);

        $code=$request->input('code');
        $nom=$request->input('nom');
        $montant=$request->input('montant');
        $show_on_report=$request->input('show_on_report');
        $ordre=$request->input('ordre');

        $n=new Frais();
        $n->code = $code;
        $n->nom=$nom;
        $n->montant=$montant;
        if($show_on_report == 1) {
            $n->show_on_report = true;
        } else {
            $n->show_on_report = false;
        }
        if($ordre) {
            $n->ordre = $ordre;
        } else {
            $max_ordre = Frais::all()->max('ordre');
            $n->ordre = $max_ordre + 1;
        }
        $n->save();
        session(['success'=>true]);
        return redirect()->action('admin\FraisController@index');
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
            'code'=>'required',
            'montant'=>'required|numeric',
        ]);

        $id =$request->input('id');
        $nom=$request->input('nom');
        $code=$request->input('code');
        $montant=$request->input('montant');
        $show_on_report=$request->input('show_on_report');
        $ordre=$request->input('ordre');

        $n=Frais::find($id);
        $n->nom=$nom;
        $n->code=$code  ;
        $n->montant=$montant;
        if($show_on_report == 1) {
            $n->show_on_report = true;
        } else {
            $n->show_on_report = false;
        }
        if($ordre) {
            $n->ordre = $ordre;
        }
        $n->save();
        session(['success'=>true]);
        return redirect()->action('admin\FraisController@index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $d=payement::where('type', '=', $id)->first();
        if(!$d){
            frais::destroy($id);
            session(['success'=>true]);
        }else
            session(['custom_error'=>trans('admin.cannot_del_frais')]);

        return redirect()->to(URL::previous());

	}

}
