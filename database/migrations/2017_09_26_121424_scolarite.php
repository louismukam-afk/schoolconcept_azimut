<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Scolarite extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('scolarite', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_cycle');
			$table->float('tranche1')->nullable;
			$table->float('tranche2')->nullable;
			$table->float('tranche3')->nullable;
			$table->float('tranche4')->nullable;
			$table->float('tranche5')->nullable;
			$table->float('total')->nullable;
			$table->integer('id_niveau');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('scolarite', function(Blueprint $table)
		{
			//
		});
	}

}
