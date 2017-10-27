<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoyenneMatiere extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('moyenne_matiere', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_matiere');
			$table->integer('id_classe');
			$table->float('max');
			$table->float('min');
			$table->float('moyenne');
			$table->integer('sequence');
			$table->string('annee_scolaire',9);

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('moyenne_matiere', function(Blueprint $table)
		{
			//
		});
	}

}
