<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\scolarite
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_classe
 * @property float|null $tranche1
 * @property float|null $tranche2
 * @property float|null $tranche3
 * @property float|null $tranche4
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereTranche1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereTranche2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereTranche3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereTranche4($value)
 * @property int $id_niveau
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereIdNiveau($value)
 * @property float $total
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereTotal($value)
 * @property int $tranche5
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereTranche5($value)
 * @property int $id_cycle
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\scolarite whereIdCycle($value)
 */
class scolarite extends Model {

	protected  $table='scolarite';

    public $fillable=['id_niveau','id_cycle','tranche1','tranche2','tranche3','tranche4',' tranche5'];

    public $timestamps=false;


}
