<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\classeMatiere
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_classe
 * @property int $id_matiere
 * @property int $coef
 * @property int|null $max
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereCoef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereIdMatiere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereMax($value)
 * @property int $id_cycle
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereIdCycle($value)
 * @property string|null $semestre
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereSemestre($value)
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereType($value)
 * @property int $id_nniveau
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\classeMatiere whereIdNniveau($value)
 */
class classeMatiere extends Model {

    protected  $table='classe_matiere';

    public $fillable=['id_cycle','id_classe','id_nniveau','id_matiere','coef','max','semestre','type'];




}
