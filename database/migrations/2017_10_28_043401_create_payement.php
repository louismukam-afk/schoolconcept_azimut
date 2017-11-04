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
