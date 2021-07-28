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



}
