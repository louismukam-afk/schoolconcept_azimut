<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEleveForeignKey extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        //Table inscription
        Schema::table('inscription', function(Blueprint $table)
        {
            DB::delete('DELETE FROM inscription WHERE id_eleve NOT IN (SELECT id FROM eleve);');
            $table->foreign('id_eleve')->references('id')->on('eleve')->onDelete('cascade');
        });

        //Table note
        Schema::table('note', function(Blueprint $table)
        {
            $table->dropForeign(['id_eleve']);
            $table->foreign('id_eleve')->references('id')->on('eleve')->onDelete('cascade');
        });

        //Table parent
        Schema::table('parent', function(Blueprint $table)
        {
            DB::delete('DELETE FROM parent WHERE id_eleve NOT IN (SELECT id FROM eleve);');
            $table->foreign('id_eleve')->references('id')->on('eleve')->onDelete('cascade');
        });

        //Table payement
        Schema::table('payement', function(Blueprint $table)
        {
            DB::delete('DELETE FROM payement WHERE id_eleve NOT IN (SELECT id FROM eleve);');
            $table->foreign('id_eleve')->references('id')->on('eleve')->onDelete('cascade');
        });

        //Table photo
        Schema::table('photos', function(Blueprint $table)
        {
            DB::delete('DELETE FROM photos WHERE eleve_id NOT IN (SELECT id FROM eleve);');
            $table->foreign('eleve_id')->references('id')->on('eleve')->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('inscription', function(Blueprint $table)
        {
            $table->dropForeign(['id_eleve']);
        });

        Schema::table('note', function(Blueprint $table)
        {
            $table->dropForeign(['id_eleve']);
            $table->foreign('id_eleve')->references('id')->on('eleve')->onDelete('restrict');

        });

        Schema::table('parent', function(Blueprint $table)
        {
            $table->dropForeign(['id_eleve']);
        });

        Schema::table('payement', function(Blueprint $table)
        {
            $table->dropForeign(['id_eleve']);
        });

        Schema::table('photos', function(Blueprint $table)
        {
            $table->dropForeign(['eleve_id']);
        });
	}

}
