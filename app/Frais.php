<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\Frais
 *
 * @property int $id
 * @property string $nom
 * @property float $montant
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Frais whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Frais whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Frais whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Frais whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\Frais whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Frais extends Model {

	public function getPrintedNom() {
	    if($this->code) {
	        return $this->code;
        }
	    return $this->nom;
    }

}
