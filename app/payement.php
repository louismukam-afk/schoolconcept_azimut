<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\payement
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_eleve
 * @property string $annee_scolaire
 * @property string $date_payement
 * @property float $montant
 * @property string $motif
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement whereDatePayement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement whereIdEleve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement whereMotif($value)
 * @property string $numero
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\payement whereNumero($value)
 */
class payement extends Model {

    protected $table='payement';

    public $fillable=['id_eleve','annee_scolaire','date_payement','montant','motif','numero'];



    public function eleve() {
        return $this->belongsTo(eleve::class, 'id_eleve');
    }

}
