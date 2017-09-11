<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\salaire
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_personnel
 * @property float $montant
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\salaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\salaire whereIdPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\salaire whereMontant($value)
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\salaire whereType($value)
 */
class salaire extends Model {

	protected $table='salaire';

    public $fillable=['id_personnel','type','montant'];


    public $timestamps=false;

}
