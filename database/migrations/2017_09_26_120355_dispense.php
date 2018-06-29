<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Dispense extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dispense', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('id_personnel');
            $table->integer('id_matiere');
            $table->integer('id_classe');
            $table->integer('jour');
            $table->integer('id_horaire');
            $table->string('annee_scolaire',9);
            $table->integer('id_nniveau')->default(0);

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('dispense', function(Blueprint $table)
		{
			//
		});
	}

}
