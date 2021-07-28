<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\parametres
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @property string $valeur
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parametres whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parametres whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parametres whereValeur($value)
 */
class parametres extends Model {

    protected  $table='parametres';

    public $fillable=['nom','valeur'];



}
