<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

class codages extends Model {

   protected  $table='codages';

    public $fillable=['nom'];

    public $timestamps=false;

      public function classe_matiere(){
        return $this->hasMany(classe_matiere::class,'id_code');
    }


}
