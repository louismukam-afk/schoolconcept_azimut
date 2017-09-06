<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\bulletin_genere
 *
 * @property int $id
 * @property int $id_classe
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\bulletin_genere whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\bulletin_genere whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\bulletin_genere whereIdClasse($value)
 * @mixin \Eloquent
 */
class bulletin_genere extends Model {

    protected  $table='bulletin_genere';

    public $fillable=['id_classe','annee_scolaire'];


    public $timestamps = false;

}
