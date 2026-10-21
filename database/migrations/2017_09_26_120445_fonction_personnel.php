<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FonctionPersonnel extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fonction_personnel', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_fonction');
			$table->integer('id_personnel');
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
		Schema::dropIfExists('fonction_personnel', function(Blueprint $table)
		{
			//
		});
	}

}
