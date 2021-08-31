<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoratoiresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('moratoires', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('date_debut');
			$table->date('date_fin');
			$table->integer('id_eleve');
			$table->string('annee_scolaire');
			$table->integer('id_specialite')->default(0);
			$table->integer('id_niveau')->default(0);
			$table->integer('id_nniveau')->default(0);
			$table->integer('activer')->default(1);
			$table->float('montant')->default(0);
			$table->string('motif');
			$table->string('montant_lettre');
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
		Schema::drop('moratoires');
	}

}
