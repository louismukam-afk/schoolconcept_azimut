<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DisciplinePersonnel extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('discipline_personnel', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('heure_arrive',50)->nullable;
            $table->integer('id_personnel');
            $table->integer('type');
            $table->date('date_d');
            $table->integer('nombre')->nullable;
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
		Schema::dropIfExists('discipline_personnel', function(Blueprint $table)
		{
			//
		});
	}

}
