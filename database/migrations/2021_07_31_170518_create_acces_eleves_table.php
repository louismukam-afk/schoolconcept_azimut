<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccesElevesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('acces_eleves', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_eleve');
			$table->integer('decision')->default(0);
			$table->string('annee_scolaire');
            $table->integer('id_creator')->default(0);
            $table->integer('id_last_editor')->default(0);
            $table->string('edition_details', 512)->nullable();
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
		Schema::drop('acces_eleves');
	}

}
