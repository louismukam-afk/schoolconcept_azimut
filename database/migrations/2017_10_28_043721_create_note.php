<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNote extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('note', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_matiere');
			$table->integer('id_eleve');
			$table->integer('sequence');
			$table->float('note');
			$table->string('annee_scolaire');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('note', function(Blueprint $table)
		{
			//
		});
	}

}
