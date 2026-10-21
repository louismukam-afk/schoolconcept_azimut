<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\moyenne_classe
 *
 * @property int $id
 * @property int $id_classe
 * @property int $sequence
 * @property string $annee_scolaire
 * @property float $moyenne
 * @property float $max
 * @property float $min
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_classe whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_classe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_classe whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_classe whereMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_classe whereMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_classe whereMoyenne($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_classe whereSequence($value)
 * @mixin \Eloquent
 */
class moyenne_classe extends Model {

    protected  $table='moyenne_classe';

    public $fillable=['id_classe','max','min','moyenne','sequence','annee_scolaire'];


    public $timestamps = false;

}
