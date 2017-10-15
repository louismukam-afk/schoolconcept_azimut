<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Decaissement extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('decaissement', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('id_personnel');
            $table->string('motif',2048);
            $table->float('montant');
            $table->date('date');
            $table->string('annee_scolaire',9);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('decaissement', function(Blueprint $table)
		{
			//
		});
	}

}
