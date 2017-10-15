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
		Schema::table('eleve', function(Blueprint $table)
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


        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eleve', function(Blueprint $table)
		{
			//
		});
	}

}
