<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeuresPrevuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('heures_prevues', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_personnel');
			$table->integer('id_classe');
			$table->integer('id_nniveau');
			$table->integer('id_matiere');
			$table->integer('total')->default(0);
			$table->string('annee_scolaire');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('heures_prevues');
	}

}
