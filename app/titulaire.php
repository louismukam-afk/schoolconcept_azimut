<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\titulaire
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_personnel
 * @property int $id_classe
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\titulaire whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\titulaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\titulaire whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\titulaire whereIdPersonnel($value)
 */
class titulaire extends Model {

	protected $table='titulaire';

    public $fillable=['id_personnel','id_classe','annee_scolaire'];


    public $timestamps=false;

}
