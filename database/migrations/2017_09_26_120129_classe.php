<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Classe extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('classe', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('code');
            $table->string('nom');
            $table->string('nom_anglais');
            $table->integer('id_niveau');
            $table->integer('id_cycle');
            $table->integer('capacite');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('classe', function(Blueprint $table)
		{
			//
		});
	}

}
