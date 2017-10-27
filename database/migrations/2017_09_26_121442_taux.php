<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Taux extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('taux', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_personnel');
			$table->float('taux');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('taux', function(Blueprint $table)
		{
			//
		});
	}

}
