<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\trimestre
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $nom
 * @property int $seq1
 * @property int $seq2
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\trimestre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\trimestre whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\trimestre whereSeq1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\trimestre whereSeq2($value)
 */
class trimestre extends Model {

	protected  $table='trimestre';

    public $fillable=['nom','seq1','seq2'];


    public $timestamps=false;

}
