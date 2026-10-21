<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\NNiveau
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\NNiveau whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\NNiveau whereNom($value)
 * @property int $id_cycle
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\NNiveau whereIdCycle($value)
 */
class NNiveau extends Model {
    protected $table='n_niveaus';
    public $fillable=['nom','id_cycle'];
	//


    public function cycle()
    {
        return $this->belongsTo(cycle::class, 'id_cycle');
    }

}
