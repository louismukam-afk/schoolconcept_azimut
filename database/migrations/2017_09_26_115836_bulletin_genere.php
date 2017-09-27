<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BulletinGenere extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bulletin_genere', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_classe');
            $table->integer('sequence');
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
		Schema::drop('bulletin_genere');
	}

}
