<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\decaissement
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_personnel
 * @property float $variation
 * @property string $motif
 * @property float $montant
 * @property float $total
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\decaissement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\decaissement whereIdPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\decaissement whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\decaissement whereMotif($value)
 * @property string $date
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\decaissement whereDate($value)
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\decaissement whereAnneeScolaire($value)
 */
class decaissement extends Model {

	protected  $table='decaissement';

    public $fillable=['id_personnel','motif','montant','date','annee_scoaire'];



}
