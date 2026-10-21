<?php namespace gesecole\Http\Controllers;

use gesecole\Campus;
use gesecole\parametres;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

    public function renewSchoolYear() {
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

    protected function currentCampusId()
    {
        $id = session('campus_id');
        if($id) {
            return $id;
        }

        $campus = Campus::orderBy('id')->first();
        if($campus) {
            $nom = $campus->nom;
            if($campus->sigle) {
                $nom .= ' - '.$campus->sigle;
            }
            session(array(
                'campus_id' => $campus->id,
                'campus_nom' => $nom,
                'campus_sigle' => $campus->sigle,
                'campus_localisation' => $campus->localisation,
                'nom_etab' => $nom
            ));
            return $campus->id;
        }

        return null;
    }

    protected function currentCampus()
    {
        $id = $this->currentCampusId();
        return $id ? Campus::find($id) : null;
    }
}
