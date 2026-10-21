<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Matiere extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('matiere', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('groupe')->default(0);
            $table->string('nom');
            $table->string('nom_anglais');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('matiere', function(Blueprint $table)
		{
			//
		});
	}

}
