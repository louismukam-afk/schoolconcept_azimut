<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayement extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payement', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_eleve');
			$table->string('annee_scolaire');
			$table->date('date_payement');
			$table->float('montant');
			$table->string('motif',1000);
			$table->string('numero',20)->nullable();
            $table->string('mode_paye');
            $table->string('ref_versement');
            $table->integer('id_inscription')->default(0);
            $table->string('motif1',1000);
            $table->string('lettre');
            $table->integer('type')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('payement', function(Blueprint $table)
		{
			//
		});
	}

}
