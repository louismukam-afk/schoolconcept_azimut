<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoyenneEleve extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('moyenne_eleve', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_eleve');
			$table->integer('id_classe');
			$table->float('moyenne');
			$table->string('annee_scolaire');
			$table->integer('sequence');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('moyenne_eleve', function(Blueprint $table)
		{
			//
		});
	}

}
