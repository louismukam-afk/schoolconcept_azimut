<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderAndRapportToFrais extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('frais', function(Blueprint $table)
		{
			$table->boolean('show_on_report')->default(FALSE);
			$table->integer('ordre')->default(1);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('frais', function(Blueprint $table)
		{
            $table->dropColumn('show_on_report');
            $table->dropColumn('ordre');
		});
	}

}
