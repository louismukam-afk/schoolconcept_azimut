<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

class moratoire extends Model {

	public function eleve()
    {
        return $this->belongsTo(eleve::class,'id_eleve');
    }
    public function specialite()
    {
        return $this->belongsTo(classe::class,'id_specialite');
    }
    public function niveau()
    {
        return $this->belongsTo(niveau::class,'id_niveau');
    }
    public  function nniveau()
    {
        return $this->belongsTo(NNiveau::class,'id_nniveau');
    }

}
