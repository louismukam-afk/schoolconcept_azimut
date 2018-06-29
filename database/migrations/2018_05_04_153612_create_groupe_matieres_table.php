<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupeMatieresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('groupe_matieres', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_classe_matiere');
			$table->integer('id_matiere_mere');
			$table->integer('id_classe');
			$table->integer('id_nniveau');
			$table->integer('semestre');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('groupe_matieres');
	}

}
