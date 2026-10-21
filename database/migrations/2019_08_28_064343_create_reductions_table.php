<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReductionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reductions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_eleve')->default(0);
			$table->integer('id_inscription')->default(0);
			$table->integer('montant')->default(0);
			$table->string('annee_scolaire');
			$table->date('date_remise');
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
		Schema::drop('reductions');
	}

}
