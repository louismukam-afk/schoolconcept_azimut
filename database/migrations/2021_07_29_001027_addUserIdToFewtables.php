<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToFewtables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('discipline', function (Blueprint $table) {
            $table->integer('id_creator')->default(0);
            $table->integer('id_last_editor')->default(0);
            $table->string('edition_details', 512)->nullable();
        });

        Schema::table('decaissement', function (Blueprint $table) {
            $table->integer('id_creator')->default(0);
            $table->integer('id_last_editor')->default(0);
            $table->string('edition_details', 512)->nullable();
        });

        Schema::table('dispense', function (Blueprint $table) {
            $table->integer('id_creator')->default(0);
            $table->integer('id_last_editor')->default(0);
            $table->string('edition_details', 512)->nullable();
        });

        Schema::table('eleve', function (Blueprint $table) {
            $table->integer('id_creator')->default(0);
            $table->integer('id_last_editor')->default(0);
            $table->string('edition_details', 512)->nullable();
        });

        Schema::table('heure_realise', function (Blueprint $table) {
            $table->integer('id_creator')->default(0);
            $table->integer('id_last_editor')->default(0);
            $table->string('edition_details', 512)->nullable();
        });

        Schema::table('inscription', function (Blueprint $table) {
            $table->integer('id_creator')->default(0);
            $table->integer('id_last_editor')->default(0);
            $table->string('edition_details', 512)->nullable();
        });

        Schema::table('payement', function (Blueprint $table) {
            $table->integer('id_creator')->default(0);
            $table->integer('id_last_editor')->default(0);
            $table->string('edition_details', 512)->nullable();
        });

        Schema::table('payement_salaire', function (Blueprint $table) {
            $table->integer('id_creator')->default(0);
            $table->integer('id_last_editor')->default(0);
            $table->string('edition_details', 512)->nullable();
        });

        Schema::table('note', function (Blueprint $table) {
            $table->integer('id_creator')->default(0);
            $table->integer('id_last_editor')->default(0);
            $table->string('edition_details', 512)->nullable();
        });

        Schema::table('salaire', function (Blueprint $table) {
            $table->integer('id_creator')->default(0);
            $table->integer('id_last_editor')->default(0);
            $table->string('edition_details', 512)->nullable();
        });

        Schema::table('scolarite', function (Blueprint $table) {
            $table->integer('id_creator')->default(0);
            $table->integer('id_last_editor')->default(0);
            $table->string('edition_details', 512)->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('discipline', function (Blueprint $table) {
            $table->dropColumn('id_creator');
            $table->dropColumn('id_last_editor');
            $table->dropColumn('edition_details');
        });

        Schema::table('decaissement', function (Blueprint $table) {
            $table->dropColumn('id_creator');
            $table->dropColumn('id_last_editor');
            $table->dropColumn('edition_details');
        });

        Schema::table('dispense', function (Blueprint $table) {
            $table->dropColumn('id_creator');
            $table->dropColumn('id_last_editor');
            $table->dropColumn('edition_details');
        });

        Schema::table('eleve', function (Blueprint $table) {
            $table->dropColumn('id_creator');
            $table->dropColumn('id_last_editor');
            $table->dropColumn('edition_details');
        });

        Schema::table('heure_realise', function (Blueprint $table) {
            $table->dropColumn('id_creator');
            $table->dropColumn('id_last_editor');
            $table->dropColumn('edition_details');
        });

        Schema::table('inscription', function (Blueprint $table) {
            $table->dropColumn('id_creator');
            $table->dropColumn('id_last_editor');
            $table->dropColumn('edition_details');
        });

        Schema::table('payement', function (Blueprint $table) {
            $table->dropColumn('id_creator');
            $table->dropColumn('id_last_editor');
            $table->dropColumn('edition_details');
        });

        Schema::table('payement_salaire', function (Blueprint $table) {
            $table->dropColumn('id_creator');
            $table->dropColumn('id_last_editor');
            $table->dropColumn('edition_details');
        });

        Schema::table('note', function (Blueprint $table) {
            $table->dropColumn('id_creator');
            $table->dropColumn('id_last_editor');
            $table->dropColumn('edition_details');
        });

        Schema::table('salaire', function (Blueprint $table) {
            $table->dropColumn('id_creator');
            $table->dropColumn('id_last_editor');
            $table->dropColumn('edition_details');
        });

        Schema::table('scolarite', function (Blueprint $table) {
            $table->dropColumn('id_creator');
            $table->dropColumn('id_last_editor');
            $table->dropColumn('edition_details');
        });
	}

}
