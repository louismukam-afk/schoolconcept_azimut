<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemisesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('remises', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->integer('id_eleve')->default(0);
            $table->integer('id_inscription')->default(0);
            $table->integer('montant')->default(0);
            $table->string('annee_scolaire');
            $table->date('date_remise');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('remises');
	}

}
