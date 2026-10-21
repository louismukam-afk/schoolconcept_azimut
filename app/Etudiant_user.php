<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

class Etudiant_user extends Model {

	protected $table='etudiant_users';

    public function inscription(){
        return $this->belongsTo(inscription::class,'id_inscription');
    }
    public function eleve(){
        return $this->belongsTo(eleve::class,'id_eleve');
    }


}
