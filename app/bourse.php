<?php

namespace gesecole;

use Illuminate\Database\Eloquent\Model;
/**
 * gesecole\bourse
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_eleve
 * @property float $montant
 * @property string $annee_scolaire
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class bourse extends Model
{
    protected $guarded = [];

    public function eleve() {
        return $this->belongsTo(eleve::class, 'id_eleve');
    }
}
