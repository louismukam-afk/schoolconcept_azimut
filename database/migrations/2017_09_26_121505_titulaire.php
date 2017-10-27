<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Titulaire extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('titulaire', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_personnel');
			$table ->integer('id_classe');
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
		Schema::dropIfExists('titulaire', function(Blueprint $table)
		{
			//
		});
	}

}
