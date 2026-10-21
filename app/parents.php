<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\parents
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @property string $profession
 * @property string $ville
 * @property string $quartier
 * @property string $tel1
 * @property string|null $tel2
 * @property int $type
 * @property int $id_eleve
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereIdEleve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereProfession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereQuartier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereTel1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereTel2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\parents whereVille($value)
 */
class parents extends Model {

    protected $table='parent';

    public $fillable=['nom','profession','ville','quartier','tel1','tel2','type','id_eleve'];



}
