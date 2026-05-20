<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampTotableBulletin extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('moyenne_classe', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
        Schema::table('moyenne_eleve', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
        Schema::table('moyenne_matiere', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
        Schema::table('bulletin_genere', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });




    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('bulletin_genere', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });
        Schema::table('moyenne_classe', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });
        Schema::table('moyenne_eleve', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });
        Schema::table('moyenne_matiere', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });
	}

}
