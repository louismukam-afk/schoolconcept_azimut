<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Salaire extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('salaire', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_personnel');
			$table->integer('type')->default(0);
			$table->float('montant')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('salaire', function(Blueprint $table)
		{
			//
		});
	}

}
