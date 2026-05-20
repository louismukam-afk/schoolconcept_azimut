<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisciplineNewPersonnelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('discipline__new_personnels', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->integer('id_horaire')->default(0);
            $table->integer('id_jour')->default(0);
            $table->integer('statuts_discipline')->default(0);
            $table->integer('id_eleve')->default(0);
            $table->integer('id_personnel')->default(0);
            $table->string('annee_scolaire',9);
            $table->integer('type');
            $table->integer('nb_heures')->default(0);
            $table->string('heure_debut');
            $table->string('heure_fin');
            $table->integer('id_classe')->default(0);
            $table->integer('id_cycle')->default(0);
            $table->integer('id_niveau')->default(0);
            $table->integer('id_n_niveau')->default(0);
            $table->integer('id_inscription')->default(0);
            $table->date('date_discipline');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('discipline__new_personnels');
	}

}
