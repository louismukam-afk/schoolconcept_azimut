<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\verrouNote
 *
 * @property int $id
 * @property int $sequence
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\verrouNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\verrouNote whereSequence($value)
 * @mixin \Eloquent
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\verrouNote whereAnneeScolaire($value)
 */
class verrouNote extends Model {

    protected  $table='verrounote';

    public $fillable=['sequence','annee_scolaire'];

    public $timestamps=false;

}
