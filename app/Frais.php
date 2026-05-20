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

    const CASH_FEES_TYPE = 0;
    const KIND_FEES_TYPE = 1;

    public function getPrintedNom() {
        if($this->code) {
            return $this->code;
        }
        return $this->nom;
    }

    public function isKind() {
        return $this->type == static::KIND_FEES_TYPE;
    }

    public function isCash() {
        return $this->type == static::CASH_FEES_TYPE;
    }

}
