<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Inscription extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inscription', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('date_inscription');
			$table->integer('id_eleve');
			$table->string('annee_scolaire',9);
			$table->integer('id_classe');
			$table->integer('id_cycle');
			$table->float('montant');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('inscription', function(Blueprint $table)
		{
			//
		});
	}

}
