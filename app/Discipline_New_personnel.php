<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

class Discipline_New_personnel extends Model {

    protected  $table='discipline__new_personnels';

    public $fillable=['id_personnel',
        'date_discipline',
        'id_personnel',
        'annee_scolaire',
        'statuts_discipline',
        'id_jour',
        'id_horaire',
        'type',
        'id_classe',
        'id_cycle',
        'id_niveau',
        'id_n_niveau',
        'heure_debut',
        'heure_fin',
        'nb_heures',
    ];
    public function classe(){
        return $this->belongsTo(classe::class,'id_classe');
    }
    public function nniveau(){
        return $this->belongsTo(NNiveau::class,'id_nniveau');
    }
    public function niveau(){
        return $this->belongsTo(niveau::class,'id_niveau');
    }
    public function cycle(){
        return $this->belongsTo(cycle::class,'id_cycle');
    }
    public function jour(){
        return $this->belongsTo(Jour::class,'id_jour');
    }
    public function horaire(){
        return $this->belongsTo(horaire::class,'id_horaire');
    }



}
