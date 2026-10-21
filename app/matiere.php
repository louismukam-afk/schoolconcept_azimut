<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * gesecole\matiere
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\dispense[] $dispenses
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\note[] $notes
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\matiere whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\matiere whereNom($value)
 * @property string $nom_anglais
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\matiere whereNomAnglais($value)
 * @property int $groupe
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\matiere whereGroupe($value)
 */
class matiere extends Model {

	protected $table='matiere';

    public $fillable=['nom','nom_anglais','groupe'];



    public function notes(){
        return $this->hasMany(note::class,'id_matiere');
    }
    public function dispenses(){
        return $this->hasMany(dispense::class,'id_matiere');

    }

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
        if(App::getLocale() == 'en') {
            if($this->nom_anglais){
                return $this->nom_anglais;
            }
        }
        return $this->nom;
    }
}
