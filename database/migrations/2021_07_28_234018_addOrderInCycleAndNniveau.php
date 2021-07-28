<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderInCycleAndNniveau extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cycle', function (Blueprint $table) {
		    $table->integer('ordre')->default(1);
        });

		Schema::table('n_niveaus', function (Blueprint $table) {
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
        Schema::table('cycle', function (Blueprint $table) {
            $table->dropColumn('ordre');
        });

        Schema::table('n_niveaus', function (Blueprint $table) {
            $table->dropColumn('ordre');
        });
	}

}
