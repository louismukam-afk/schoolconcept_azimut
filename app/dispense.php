<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\dispense
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_personnel
 * @property int $id_matiere
 * @property int $id_classe
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereIdMatiere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereIdPersonnel($value)
 * @property int $jour
 * @property int $id_horaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereIdHoraire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereJour($value)
 * @property int $id_nniveau
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereIdNniveau($value)
 */
class dispense extends Model {

	//
    protected $table='dispense';

    public $fillable=['id_personnel','id_classe','id_nniveau','id_matiere','jour','id_horaire','annee_scolaire'];



}
