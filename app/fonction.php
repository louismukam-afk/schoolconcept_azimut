<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\fonction
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\gesecole\fonction_personnel[] $fonction_personnels
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\fonction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\fonction whereNom($value)
 */
class fonction extends Model {

	protected $table='fonction';

    public $fillable=['nom'];


    public $timestamps=false;
    public function fonction_personnels(){
        return $this->hasMany(fonction_personnel::class,'id_fonction');

    }

}
