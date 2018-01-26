<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

/**
 * gesecole\NNiveau
 *
 * @mixin \Eloquent
 */
class NNiveau extends Model {
    protected $table='n_niveaus';

    public $fillable=['nom'];
	//

}
