<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoyenneClasse extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('moyenne_classe', function(Blueprint $table)
		{
		    $table->increments('id');
		    $table->integer('id_classe');
		    $table->integer('sequence');
		    $table->string('annee_scolaire',9);
		    $table->float('moyenne');
		    $table->float('max');
		    $table->float('min');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('moyenne_classe', function(Blueprint $table)
		{
			//
		});
	}

}
