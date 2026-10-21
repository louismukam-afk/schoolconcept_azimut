<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

class PresencePermanent extends Model {
    protected $table='presence_permanents';

    public $fillable=[
        'id_biometrie_import','id_personnel','id_emploi_permanent','id_plage_horaire',
        'date_presence','jour_semaine','heure_debut_prevue','heure_fin_prevue',
        'heure_debut_reelle','heure_fin_reelle','duree_prevue','duree_realisee',
        'salaire_journalier','montant_du','penalite_montant','statut','observation','id_user'
    ];

    public function import(){ return $this->belongsTo(BiometrieImport::class,'id_biometrie_import'); }
    public function personnel(){ return $this->belongsTo(personnel::class,'id_personnel'); }
    public function emploi(){ return $this->belongsTo(EmploiPermanent::class,'id_emploi_permanent'); }
    public function plage(){ return $this->belongsTo(PlageHoraire::class,'id_plage_horaire'); }
}
