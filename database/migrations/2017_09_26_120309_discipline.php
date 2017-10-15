<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Discipline extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('discipline', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('id_eleve');
            $table->string('annee_scolaire',9);
            $table->integer('abscence_inj')->default(0);
            $table->integer('abscence_just')->default(0);
            $table->integer('retard')->default(0);
            $table->integer('avertissement')->default(0);
            $table->integer('blame')->default(0);
            $table->integer('exclusion')->default(0);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('discipline', function(Blueprint $table)
		{
			//
		});
	}

}
