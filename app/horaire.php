<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\horaire
 *
 * @property int $id
 * @property string $intervalle
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\horaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\horaire whereIntervalle($value)
 * @mixin \Eloquent
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\horaire whereType($value)
 * @property int $ordre
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\horaire whereOrdre($value)
 */
class horaire extends Model {

    protected $table='horaire';

    public $fillable=['intervalle','type','ordre'];

    public function discipline_new_Etd(){
        return $this->hasMany(Discipline_New_Etd::class,'id_horaire');
    }
    public function discipline_personnel(){
        return $this->hasMany(Discipline_New_personnel::class,'id_horaire');
    }

}
