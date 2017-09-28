<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\heure_realise
 *
 * @property int $id
 * @property int $id_personnel
 * @property int $nombre
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereIdPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereNombre($value)
 * @mixin \Eloquent
 */
class heure_realise extends Model {

	
    protected $table='heure_realise';

    public $fillable=['id_personnel','nombre'];


    public $timestamps=false;

}
