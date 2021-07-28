<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\niveau
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\classe[] $classes
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @property int $id_cycle
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\niveau whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\niveau whereNom($value)
 * @property-read \gesecole\scolarite $scolarite
 * @property string $nom_anglais
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\niveau whereNomAnglais($value)
 */
class niveau extends Model {

	protected $table='niveau';

    public $fillable=['nom'];



    public function getNom_anglais()
    {
        if($this->nom_anglais==null){
            return $this->nom;
        }
        else
            return $this->nom_anglais;
    }

    public function getNom()
    {
        if($this->nom==null){
            return $this->nom_anglais;
        }
        else
            return $this->nom;
    }

    public function classes(){
        return $this->hasMany(classe::class,'id_niveau');
    }

    public function scolarite(){
        return $this->hasOne(scolarite::class,'id_niveau');

    }

}
