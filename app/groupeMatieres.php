<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

class groupeMatieres extends Model {

	public $fillable=['id_classe_matiere','id_matiere_mere','id_classe','semestre'];


    public $timestamps=false;

}
