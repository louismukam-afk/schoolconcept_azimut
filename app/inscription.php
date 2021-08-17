<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\inscription
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $date_inscription
 * @property int $id_eleve
 * @property string $annee_scolaire
 * @property int $id_classe
 * @property int $id_cycle
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\inscription whereAnneeScolaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\inscription whereDateInscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\inscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\inscription whereIdClasse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\inscription whereIdCycle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\inscription whereIdEleve($value)
 * @property int $montant
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\inscription whereMontant($value)
 * @property-read \gesecole\classe $classe
 * @property-read \gesecole\eleve $eleve
 * @property int $id_nniveau
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\inscription whereIdNniveau($value)
 */
class inscription extends Model {

	protected  $table='inscription';

    public $fillable=['date_inscription','id_eleve','id_nniveau','id_classe','id_cycle','annee_scolaire','montant'];


    public function classe(){
        return $this->belongsTo(classe::class,'id_classe');
    }
    public function nniveau(){
            return $this->belongsTo(NNiveau::class,'id_nniveau');
    }

    public function cycle(){
        return $this->belongsTo(cycle::class,'id_cycle');
    }

    public function eleve(){
       return $this->belongsTo(eleve::class,'id_eleve');
    }

    public function Etudiant_users()
    {
        return $this->belongsTo(Etudiant_user::class,'id_inscription');
    }

}
