<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    //Table n_niveaus
		Schema::table('n_niveaus', function(Blueprint $table)
		{
			$table->foreign('id_cycle')->references('id')->on('cycle')->onDelete('restrict');
		});

        //Table classe_matiere

        Schema::table('classe_matiere', function(Blueprint $table)
        {
            $table->foreign('id_nniveau')->references('id')->on('n_niveaus')->onDelete('restrict');
            $table->foreign('id_cycle')->references('id')->on('cycle')->onDelete('restrict');
            $table->foreign('id_classe')->references('id')->on('classe')->onDelete('restrict');
            $table->foreign('id_matiere')->references('id')->on('matiere')->onDelete('restrict');
        });

        //Table classe

        Schema::table('classe', function(Blueprint $table)
		{
			$table->foreign('id_niveau')->references('id')->on('niveau')->onDelete('restrict');
		});

        //Table scolarite

		Schema::table('scolarite', function(Blueprint $table)
		{
			$table->foreign('id_niveau')->references('id')->on('niveau')->onDelete('restrict');
			$table->foreign('id_cycle')->references('id')->on('cycle')->onDelete('restrict');
		});


        //Table dispense

        Schema::table('dispense', function(Blueprint $table)
        {
            $table->foreign('id_matiere')->references('id')->on('matiere')->onDelete('restrict');
            $table->foreign('id_classe')->references('id')->on('classe')->onDelete('restrict');
            $table->foreign('id_horaire')->references('id')->on('horaire')->onDelete('restrict');
            $table->foreign('id_nniveau')->references('id')->on('n_niveaus')->onDelete('restrict');
            //$table->foreign('id_salle')->references('id')->on('salles')->onDelete('restrict');
        });

        //Table fonction_personnel

        Schema::table('fonction_personnel', function(Blueprint $table)
        {
            $table->foreign('id_fonction')->references('id')->on('fonction')->onDelete('restrict');
        });

        //Table groupe_matieres

        Schema::table('groupe_matieres', function(Blueprint $table)
        {
            $table->foreign('id_nniveau')->references('id')->on('n_niveaus')->onDelete('restrict');
        });

        // Heure_realise
        Schema::table('heure_realise', function(Blueprint $table)
        {
            //$table->foreign('id_classe')->references('id')->on('classe')->onDelete('restrict');
            //$table->foreign('id_nniveau')->references('id')->on('n_niveaus')->onDelete('restrict');
            //$table->foreign('id_matiere')->references('id')->on('matiere')->onDelete('restrict');
        });

        // Heures_prevues

        Schema::table('heures_prevues', function(Blueprint $table)
        {
            //$table->foreign('id_classe')->references('id')->on('classe')->onDelete('restrict');
            //$table->foreign('id_nniveau')->references('id')->on('n_niveaus')->onDelete('restrict');
            //$table->foreign('id_matiere')->references('id')->on('matiere')->onDelete('restrict');
        });

        // Inscription
        Schema::table('inscription', function(Blueprint $table)
        {
            $table->foreign('id_classe')->references('id')->on('classe')->onDelete('restrict');
            $table->foreign('id_nniveau')->references('id')->on('n_niveaus')->onDelete('restrict');
            $table->foreign('id_cycle')->references('id')->on('cycle')->onDelete('restrict');
        });

        // Note
        Schema::table('note', function(Blueprint $table)
        {
            $table->foreign('id_matiere')->references('id')->on('matiere')->onDelete('restrict');
            $table->foreign('id_eleve')->references('id')->on('eleve')->onDelete('restrict');
        });




	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('n_niveaus', function(Blueprint $table)
		{
			$table->dropForeign(['id_cycle']);
		});

		Schema::table('classe', function(Blueprint $table)
		{
			$table->dropForeign(['id_niveau']);
		});

		Schema::table('scolarite', function(Blueprint $table)
		{
			$table->dropForeign(['id_niveau']);
			$table->dropForeign(['id_cycle']);
		});

		Schema::table('classe_matiere', function(Blueprint $table)
		{
			$table->dropForeign(['id_nniveau']);
			$table->dropForeign(['id_cycle']);
			$table->dropForeign(['id_classe']);
			$table->dropForeign(['id_matiere']);
		});



        Schema::table('dispense', function(Blueprint $table)
        {
            $table->dropForeign(['id_nniveau']);
            $table->dropForeign(['id_horaire']);
            $table->dropForeign(['id_classe']);
            //$table->dropForeign(['id_salle']);
            $table->dropForeign(['id_matiere']);
        });

        Schema::table('fonction_personnel', function(Blueprint $table)
        {
            $table->dropForeign(['id_fonction']);
        });

        Schema::table('groupe_matieres', function(Blueprint $table)
        {
            $table->dropForeign(['id_nniveau']);
        });

//        Schema::table('heure_realise', function(Blueprint $table)
//        {
//            $table->dropForeign(['id_classe']);
//            $table->dropForeign(['id_nniveau']);
//            $table->dropForeign(['id_matiere']);
//        });
//
//        Schema::table('heures_prevues', function(Blueprint $table)
//        {
//            $table->dropForeign(['id_classe']);
//            $table->dropForeign(['id_nniveau']);
//            $table->dropForeign(['id_matiere']);
//        });

        Schema::table('inscription', function(Blueprint $table)
        {
            $table->dropForeign(['id_classe']);
            $table->dropForeign(['id_nniveau']);
            $table->dropForeign(['id_cycle']);
        });

        Schema::table('note', function(Blueprint $table)
        {
            $table->dropForeign(['id_matiere']);
            $table->dropForeign(['id_eleve']);
        });




	}

}
