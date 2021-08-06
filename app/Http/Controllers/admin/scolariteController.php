<?php namespace gesecole\Http\Controllers\admin;

use gesecole\classe;
use gesecole\cycle;
use gesecole\Http\Controllers\Controller;
use gesecole\niveau;
use gesecole\parametres;
use Illuminate\Support\Facades\Session;
use gesecole\scolarite;
use Illuminate\Http\Request;

class scolariteController extends Controller {

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
	    $s0=scolarite::all();
	    $arr=[];
	    foreach ($s0 as $sco){
	        array_push($arr,$sco->id_niveau);
        }

        $s=scolarite::paginate(15);
        $c=niveau::all();
        $cy=cycle::all();
        $values= array('titre'=>trans('admin.schoolfees'),'scolarite'=>$s,'niveaux'=>$c,'cycles'=>$cy);
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }
        return view('admin/scolarite',$values);
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
	public function store(Request $req)
	{

        $this->validate($req,[
            'classe'=>'required|numeric',
            'cycle'=>'required|numeric',
            'inscription'=>'required|numeric',
            'tranche1'=>'required|numeric',
            'tranche2'=>'required|numeric',
            'tranche3'=>'numeric',
            'tranche4'=>'numeric',
            'tranche5'=>'numeric'
        ]);

        $classe=$req->input('classe');
        $cycle=$req->input('cycle');
        $inscription=$req->input('inscription',0);
        $tranche1=$req->input('tranche1',0);
        $tranche2=$req->input('tranche2');
        $tranche3=$req->input('tranche3');
        $tranche4=$req->input('tranche4');
        $tranche5=$req->input('tranche5');

        $s0=scolarite::whereIdCycle($cycle)->where('id_niveau','=',$classe)->first();
        $current_user = \Auth::user();

        if($s0!=null)
        {
            $s0->id_niveau=$classe;
            $s0->id_cycle=$cycle;
            $s0->inscription=$inscription;
            $s0->tranche1=floatval($tranche1);
            $s0->tranche2=floatval($tranche2);
            $s0->tranche3=floatval($tranche3);
            $s0->tranche4=floatval($tranche4);
            $s0->tranche5=floatval($tranche5);
            $s0->total=$tranche1+$tranche2+$tranche3+$tranche4+$tranche5;

            $s0->id_last_editor = $current_user->id;

            $s0->save();
        }else{
            $s=new scolarite();
            $s->id_niveau=$classe;
            $s->id_cycle=$cycle;
            $s->inscription=$inscription;
            $s->tranche1=floatval($tranche1);
            $s->tranche2=floatval($tranche2);
            $s->tranche3=floatval($tranche3);
            $s->tranche4=floatval($tranche4);
            $s->tranche5=floatval($tranche5);
            $s->total=$tranche1+$tranche2+$tranche3+$tranche4+$tranche5;

            $s->id_creator = $current_user->id;
            $s->id_last_editor = $current_user->id;
            $s->edition_details = 'total : ' . $s->total. ', inscription : ' . $inscription;

            $s->save();

        }


        session(['success'=>true]);
        return redirect()->action('admin\scolariteController@index');
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
	public function update(Request $req)
	{
        $this->validate($req,[
            'id'=>'required|numeric',
            'classes'=>'required|numeric',
            'inscription'=>'required|numeric',
            'cycle'=>'required|numeric',
            'tranche1'=>'required|numeric',
            'tranche2'=>'required|numeric',
            'tranche3'=>'numeric',
            'tranche4'=>'numeric',
            'tranche5'=>'numeric',
        ]);
        $id=$req->input('id');
        $classe=$req->input('classes');
        $cycle=$req->input('cycle');
        $inscription=$req->input('inscription');
        $tranche1=$req->input('tranche1');
        $tranche2=$req->input('tranche2');
        $tranche3=$req->input('tranche3');
        $tranche4=$req->input('tranche4');
        $tranche5=$req->input('tranche5');
        $s=scolarite::find($id);
        $s->id_niveau=$classe;
        $s->id_cycle=$cycle;
        $s->inscription=$inscription;
        $s->tranche1=floatval($tranche1);
        $s->tranche2=floatval($tranche2);
        $s->tranche3=floatval($tranche3);
        $s->tranche4=floatval($tranche4);
        $s->tranche5=floatval($tranche5);
        $s->total=$tranche1+$tranche2+$tranche3+$tranche4+$tranche5;

        $current_user = \Auth::user();
        $s->id_last_editor = $current_user->id;

        $s->save();
        session(['success'=>true]);
        return redirect()->action('admin\scolariteController@index');
	}


	public function getScolarite(Request $request)
    {
        $cycle=$request->input('cycle');
        $niveau=$request->input('niveau');
        if($cycle==null || $niveau==null){
            $s=new scolarite();
            $s->inscription=0;
            return response()->json($s);
        }

        $s=scolarite::whereIdCycle($cycle)->where('id_niveau','=',$niveau)->first();
        if($s==null){
            $s=new scolarite();
            $s->inscription=0;
            return response()->json($s);
        }

        return response()->json($s);
    }
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $c=scolarite::find($id);
        $c->delete();
        session(['success'=>true]);
        return redirect()->action('admin\scolariteController@index');
	}

}
