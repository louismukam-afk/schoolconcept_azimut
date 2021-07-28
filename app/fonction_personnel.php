<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\fonction_personnel
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_fonction
 * @property int $id_personnel
 * @property int $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\fonction_personnel whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\fonction_personnel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\fonction_personnel whereIdFonction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\fonction_personnel whereIdPersonnel($value)
 */
class fonction_personnel extends Model {

    protected $table='fonction_personnel';

    public $fillable=['id_fonction','id_personnel','annee_scolaire'];



}
