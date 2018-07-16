<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Horaire extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('horaire', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('intervalle',11);
			$table->integer('type')->default(0);
			$table->double('nb_heures')->default(1);
			$table->integer('ordre');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('horaire', function(Blueprint $table)
		{
			//
		});
	}

}
