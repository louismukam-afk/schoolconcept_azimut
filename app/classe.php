<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;


/**
 * gesecole\classe
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\classeMatiere[] $classe_matieres
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\dispense[] $dispenses
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\titulaire[] $titulaires
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @property int $id_niveau
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classe whereIdNiveau($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classe whereNom($value)
 * @property int $capacite
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classe whereCapacite($value)
 * @property-read \gesecole\niveau $niveau
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\inscription[] $inscriptions
 */
class classe extends Model {
     protected  $table='classe';

    public $fillable=['nom','id_niveau','capacite'];


    public $timestamps = false;


    public function titulaires(){
        return $this->hasMany(titulaire::class ,'id_classe');
}

    public function classe_matieres(){
        return $this->hasMany(classeMatiere::class,'id_classe');

    }
    public function dispenses(){
        return $this->hasMany(dispense::class,'id_classe');

    }

    public  function niveau(){
        return $this->belongsTo(niveau::class,'id_niveau');
    }

    public function inscriptions(){
        return $this->hasMany(inscription::class,'id_classe');
    }

}
