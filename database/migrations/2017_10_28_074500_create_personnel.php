<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonnel extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('personnel', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('email')->nullable();
            $table->string('nom');
            $table->date('date_naiss');
            $table->string('lieu_naiss');
            $table->string('nationalite')->nullable();
            $table->string('diplome');
            $table->string('sexe',1);
            $table->date('date_entree');
            $table->string('tel1');
            $table->string('tel2')->nullable();
            $table->string('adresse');
            $table->integer('type')->default(0);
            $table->string('matieres',2000)->nullable();
            $table->string('autre_information',2000)->nullable();
            $table->string('matricule')->nullable();
            $table->string('num_contribuable')->nullable();
            $table->string('num_ss')->nullable();
            $table->string('categorie')->nullable();
            $table->string('echelon')->nullable();




        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personnel', function(Blueprint $table)
        {
            //
        });
    }

}
