<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillIdinscriptionInPayment extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$inscription = \gesecole\inscription::all(['id', 'id_eleve', 'annee_scolaire']);
		foreach ($inscription as $i) {
		    \gesecole\payement::where('id_eleve', '=', $i->id_eleve)
                ->where('annee_scolaire', '=', $i->annee_scolaire)
                ->where('motif', '=', 'Inscription')
                ->update(['id_inscription' => $i->id, 'type' => -1]);
        }

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
