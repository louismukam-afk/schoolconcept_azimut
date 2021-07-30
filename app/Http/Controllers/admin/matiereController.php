<?php namespace gesecole\Http\Controllers\admin;

use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\App;

use Illuminate\Http\Request;
use gesecole\matiere;
use gesecole\parametres;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class matiereController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     *
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
    public function index(Request $request)
    {
        $titre=trans('admin.titre_matiere');
        $values= array('titre'=>$titre);

        $search_term = $request->input('search');
        if($search_term) {
            $values['search_term']=$search_term;
            $terms = explode(' ', $search_term);
            $rawSql = '';
            $i = 0;
            $processedTerms = [];
            foreach ($terms as $t) {
                if($i != 0) {
                    $rawSql .= ' OR ';
                }
                $rawSql .=  (App::getLocale()=='en'?'nom_anglais':'nom') . ' like ?';
                $processedTerms[$i] = '%' . $t . '%';
                $i++;
            }


            $m=matiere::whereRaw($rawSql, $processedTerms)
                ->orderBy((App::getLocale()=='en')?'nom_anglais':'nom')->paginate(50);

        } else {
            $m=matiere::orderBy((App::getLocale()=='en')?'nom_anglais':'nom')->paginate(50);
        }

        $values['matieres']=$m;

        if(session('success')){
            Session::forget('success');
            $values['success']=true;

        }
        return view('admin/matiere',$values);
    }

    /**
     * Show the form for creating a new resource.
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
            'nom'=>'required',
        ]);
        $c=new matiere();
        $c->nom=$req->input('nom');
        $c->nom_anglais=$req->input('nom_anglais');
        if($req->input('groupe')!=null)
            $c->groupe=  $req->input('groupe');
        $c->save();
        session(['success'=>true]);
        return redirect()->action('admin\matiereController@index');
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
            'nom'=>'required',
        ]);
        $id=$req->input('id');
        $c=matiere::find($id);
        $c->nom=$req->input('nom');
        $c->nom_anglais=$req->input('nom_anglais');
        if($req->input('groupe')!=null)
            $c->groupe=  $req->input('groupe');
        $c->save();

        return redirect()->action('admin\matiereController@index');

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
            matiere::destroy([$id]);
            session(['success'=>true]);
        } catch (QueryException $fe) {
            return  redirect()->to(URL::previous())->withErrors(['constraint_violation' => trans('admin.cannot_delete')]);
        }


    	return redirect()->to(URL::previous());


    }


}
