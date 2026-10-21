<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\Photo
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $eleve_id
 * @property string $annee_scolaire
 * @property string $url
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Photo whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Photo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Photo whereEleveId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Photo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Photo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Photo whereUrl($value)
 */
class Photo extends Model {

    public $fillable=['eleve_id','url','annee_scolaire'];


}
