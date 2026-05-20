<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

class Session_exam extends Model {

    protected $table = 'sessions';

    protected $fillable = [
        'cc1', 'tpe1', 'sn1', 'ratrappage1',
        'cc2', 'tpe2', 'sn2', 'ratrappage2',
        'id_classe_matiere', 'id_matiere', 'annee_academique',
    ];

    public function classeMatiere()
    {
        return $this->belongsTo(classeMatiere::class, 'id_classe_matiere');
    }
    public function Matiere()
    {
        return $this->belongsTo(matiere::class, 'id_matiere');
    }

    public function note()
    {
        return $this->hasMany(note::class, 'id_session');
    }
}
