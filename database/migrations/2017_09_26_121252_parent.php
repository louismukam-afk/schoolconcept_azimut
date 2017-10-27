<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Parents extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('parent', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nom')->nullable;
			$table->string('profession')->nullable;
			$table->string('ville')->nullable;
			$table->string('quartier')->nullable;
			$table->string('tel1')->nullable;
			$table->string('tel2')->nullable;
			$table->integer('type');
			$table->integer('id_eleve');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('parent', function(Blueprint $table)
		{
			//
		});
	}

}
