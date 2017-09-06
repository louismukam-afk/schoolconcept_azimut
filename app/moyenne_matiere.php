<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\moyenne_matiere
 *
 * @property int $id
 * @property int $id_matiere
 * @property int $id_classe
 * @property float $max
 * @property float $min
 * @property float $moyenne
 * @property int $sequence
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_matiere whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_matiere whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_matiere whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_matiere whereIdMatiere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_matiere whereMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_matiere whereMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_matiere whereMoyenne($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_matiere whereSequence($value)
 * @mixin \Eloquent
 */
class moyenne_matiere extends Model {

    protected  $table='moyenne_matiere';

    public $fillable=['id_matiere','id_classe','max','min','moyenne','sequence','annee_scolaire'];


    public $timestamps = false;

}
