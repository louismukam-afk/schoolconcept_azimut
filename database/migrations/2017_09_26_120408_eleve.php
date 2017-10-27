<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Eleve extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eleve', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('nom');
            $table->string('prenom')->nullable();
            $table->date('date_naiss');
            $table->string('lieu_naiss');
            $table->string('sexe',1);
            $table->string('nationalite');
            $table->string('quartier');
            $table->string('lien_tuteur')->nullable();
            $table->string('matricule')->nullable();
            $table->string('email')->nullable();
            $table->string('tel')->nullable();
            $table->string('status_matrimonial')->nullable;
            $table->string('region_origine')->nullable;
            $table->string('departement_origine')->nullable;
            $table->string('diplome_preinscription')->nullable;
            $table->string('annee_obtention')->nullable;
            $table->string('pays_obtention')->nullable;
            $table->string('dernier_etablissement')->nullable;
            $table->string('annee_sortie')->nullable;
            $table->string('diplome_obtenu')->nullable;
            $table->string('premiere_langue')->nullable;
            $table->string('deuxieme_langue')->nullable;
            $table->string('autre_langue')->nullable;
            $table->string('profession')->nullable;


        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('eleve', function(Blueprint $table)
		{
			//
		});
	}

}
