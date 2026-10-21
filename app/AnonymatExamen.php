<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

class AnonymatExamen extends Model
{
    protected $table = 'anonymats_examens';

    protected $fillable = array(
        'scope_key', 'code', 'rang', 'id_eleve', 'id_classe', 'id_cycle',
        'id_nniveau', 'id_matiere', 'id_classe_matiere', 'id_campus',
        'id_tutelle', 'annee_scolaire', 'semestre', 'type_examen',
        'mode_generation', 'id_creator', 'id_last_editor'
    );

    public function eleve()
    {
        return $this->belongsTo(eleve::class, 'id_eleve');
    }

    public function matiere()
    {
        return $this->belongsTo(matiere::class, 'id_matiere');
    }

    public function classe()
    {
        return $this->belongsTo(classe::class, 'id_classe');
    }
}
