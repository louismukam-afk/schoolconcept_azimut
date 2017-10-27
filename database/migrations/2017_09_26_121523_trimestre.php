<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Trimestre extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('trimestre', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nom');
			$table->integer('seq1');
			$table->integer('seq2');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('trimestre', function(Blueprint $table)
		{
			//
		});
	}

}
