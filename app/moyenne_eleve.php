<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\moyenne_eleve
 *
 * @property int $id
 * @property int $id_eleve
 * @property float $moyenne
 * @property string $annee_scolaire
 * @property int $sequence
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_eleve whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_eleve whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_eleve whereIdEleve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_eleve whereMoyenne($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_eleve whereSequence($value)
 * @mixin \Eloquent
 * @property int $id_classe
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\moyenne_eleve whereIdClasse($value)
 */
class moyenne_eleve extends Model {

    protected  $table='moyenne_eleve';

    public $fillable=['id_eleve','id_classe','moyenne','sequence','annee_scolaire'];


    public $timestamps = false;

}
