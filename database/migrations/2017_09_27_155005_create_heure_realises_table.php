<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeureRealisesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('heure_realise', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_personnel');
			$table->integer('nombre')->default(0);
			$table->date('date_heure_realisee');
			$table->float('taux')->default(0);
			$table->integer('jour')->default(0);
			$table->string('intervalle')->nullable();
			$table->date('date_enregistrement');
			$table->integer('id_classe')->default(0);
            $table->integer('id_nniveau')->default(0);
            $table->integer('id_matiere')->default(0);

            $table->string('annee_scolaire');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('heure_realise');
	}

}
