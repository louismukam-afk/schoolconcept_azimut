<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\dispense
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_personnel
 * @property int $id_matiere
 * @property int $id_classe
 * @property string $annee_scolaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereIdMatiere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereIdPersonnel($value)
 * @property int $jour
 * @property int $id_horaire
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereIdHoraire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereJour($value)
 * @property int $id_nniveau
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\dispense whereIdNniveau($value)
 */
class dispense extends Model {

	//
    protected $table='dispense';

    public $fillable=['id_personnel','id_classe','id_nniveau','id_matiere','jour','id_horaire','annee_scolaire'];


    public function personnel() {
        return $this->belongsTo(personnel::class, 'id_personnel');
    }

    public function matiere() {
        return $this->belongsTo(matiere::class, 'id_matiere');
    }


    public function classe() {
        return $this->belongsTo(classe::class, 'id_classe');
    }

    public function horaire() {
        return $this->belongsTo(horaire::class, 'id_horaire');
    }

    public function nniveau() {
        return $this->belongsTo(NNiveau::class, 'id_nniveau');
    }

    public function salle() {
        return $this->belongsTo(salle::class, 'id_salle');
    }

}
