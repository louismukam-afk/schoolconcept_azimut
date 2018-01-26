<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClasseMatiere extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('classe_matiere', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('id_classe');
            $table->integer('id_matiere');
            $table->integer('coef');
            $table->integer('max');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('classe_matiere', function(Blueprint $table)
		{
			//
		});
	}

}
