<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditClasseMatiere extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('classe_matiere', function(Blueprint $table)
		{
			$table->integer("semestre")->default(0);
			$table->integer("type")->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('classe_matiere', function(Blueprint $table)
		{
		    if (Schema::hasColumn('classe_matiere','semestre'))
			    $table->dropColumn("semestre");
		});
	}

}
