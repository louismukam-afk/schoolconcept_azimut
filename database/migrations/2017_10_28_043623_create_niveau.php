<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNiveau extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('niveau', function(Blueprint $table)
		{
			$table->increments('id');
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
		Schema::dropIfExists('niveau', function(Blueprint $table)
		{
			//
		});
	}

}
