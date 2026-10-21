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
 * @property string $date_heure_realisee
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\heure_realise whereDateHeureRealisee($value)
 */
class heure_realise extends Model {

	
    protected $table='heure_realise';

    public $fillable=['id_personnel','nombre','date_heure_realisee','annee_scolaire'];



}
