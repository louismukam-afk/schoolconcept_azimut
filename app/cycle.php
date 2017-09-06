<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\cycle
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\niveau[] $niveaux
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\cycle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\cycle whereNom($value)
 */
class cycle extends Model {

	protected  $table='cycle';

   public $fillable=['nom'];

    public $timestamps=false;

    public function niveaux(){
        return $this->hasMany(niveau::class,'id_cycle');
    }
}
