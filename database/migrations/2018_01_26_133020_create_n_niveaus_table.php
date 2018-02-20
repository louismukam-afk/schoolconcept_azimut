<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNNiveausTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('n_niveaus', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nom');
			$table->integer('id_cycle')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('n_niveaus', function(Blueprint $table)
        {
            //
        });
	}

}
