<?php

namespace gesecole\Http\Controllers\inscriptions;

use gesecole\bourse;
use gesecole\eleve;
use gesecole\inscription;
use Illuminate\Http\Request;
use gesecole\Http\Controllers\Controller;
use gesecole\Services\BourseService;
use Illuminate\Support\Facades\URL;

class bourseController extends Controller

{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bourses = bourse::with('eleve')->orderBy('annee_scolaire', 'desc')->paginate(50);
        $eleves = eleve::join('inscription', 'inscription.id_eleve', '=', 'eleve.id')
            ->where('annee_scolaire', '=', session('year'))
            ->select(['eleve.id', 'matricule', 'nom', 'prenom','inscription.type_scolarite', 'inscription.id as id_inscription'])
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();
        return view('inscriptions.bourses.index')->with('bourses', $bourses)
            ->with('eleves', $eleves)
            ->with('titre', trans('admin.bourse'));

        // $elevess = eleve::inscrits()->orderBy('nom')->orderBy('prenom')->get();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "id_eleve" => "required|numeric",
            "montant" => "required|numeric",
            "annee_scolaire" => "required"
        ]);
        $nn= new bourse();
        $nn->id_eleve=$request->input('id_eleve');
        $nn->montant=$request->input('montant');
        $nn->annee_scolaire=$request->input('annee_scolaire');
        $nn->save();

        //BourseService::create(new bourse($request->toArray()));
        return redirect(URL::previous())->with('success', trans('admin.succes'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            "id" => "required|numeric",
            "montant" => "required|numeric",
            "annee_scolaire" => "required"
        ]);
        $nn = bourse::find($request->input('id'));
       // $nn->id_eleve=$request->input('id_eleve');
        $nn->montant=$request->input('montant');
        $nn->annee_scolaire=$request->input('annee_scolaire');
        $nn->save();
       // BourseService::update(new bourse($request->toArray()));
        return redirect(URL::previous())->with('success', trans('admin.succes'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        BourseService::delete($id);
        return redirect(URL::previous())->with('success', trans('admin.succes'));
    }
}
