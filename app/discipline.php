<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\discipline
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_eleve
 * @property string $annee_scolaire
 * @property int $retard
 * @property int $avertissement
 * @property int $blame
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereAvertissement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereBlame($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereIdEleve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereRetard($value)
 * @property int $abscence_just
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereAbscenceJust($value)
 * @property int $abscence_inj
 * @property int $exclusion
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereAbscenceInj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\discipline whereExclusion($value)
 */
class discipline extends Model {

	protected  $table='discipline';

    public $fillable=['id_eleve','annee_scolaire','abscence_inj','abscence_just','retard','avertissement','blame','exclusion'];


    public $timestamps=false;

}
