<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

class Jour extends Model {

    protected  $table='jours';

    public $fillable=['nom','observation'];


    public function discipline_new_Etd(){
        return $this->hasMany(Discipline_New_Etd::class,'id_jour');
    }
    public function discipline_personnel(){
        return $this->hasMany(Discipline_New_personnel::class,'id_jour');
    }

}
