<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Parametres extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('parametres', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nom');
			$table->string('valeur',500)->nullable;
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('parametres', function(Blueprint $table)
		{
			//
		});
	}

}
