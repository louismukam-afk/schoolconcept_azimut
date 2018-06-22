<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\groupeMatieres
 *
 * @property int $id
 * @property int $id_classe_matiere
 * @property int $id_matiere_mere
 * @property int $id_classe
 * @property int $semestre
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\groupeMatieres whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\groupeMatieres whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\groupeMatieres whereIdClasseMatiere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\groupeMatieres whereIdMatiereMere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\groupeMatieres whereSemestre($value)
 * @mixin \Eloquent
 */
class groupeMatieres extends Model {

	public $fillable=['id_classe_matiere','id_matiere_mere','id_classe','semestre'];


    public $timestamps=false;

}
