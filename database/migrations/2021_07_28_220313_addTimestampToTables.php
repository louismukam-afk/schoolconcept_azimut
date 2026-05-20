<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampToTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
       /*  Schema::table('classe', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('classe_matiere', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));        });

        Schema::table('cycle', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));        });

        Schema::table('decaissement', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('discipline', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('discipline_personnel', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('dispense', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('eleve', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('fonction', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('fonction_personnel', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('groupe_matieres', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('heure_realise', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('horaire', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('inscription', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('matiere', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('n_niveaus', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('niveau', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('note', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('parametres', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        }); */

        Schema::table('parent', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
       

        Schema::table('payement', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('payement_salaire', function(Blueprint $table)
        {
                        $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('personnel', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('salaire', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('scolarite', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('taux', function(Blueprint $table)
        {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('verrou_note', function(Blueprint $table)
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
        Schema::table('classe', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('classe_matiere', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('cycle', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('decaissement', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('discipline', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('discipline_personnel', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('dispense', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('eleve', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('fonction', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('fonction_personnel', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('groupe_matieres', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('heure_realise', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('horaire', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('inscription', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('matiere', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('n_niveaus', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('niveau', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('note', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('parametres', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('parent', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('payement', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('payement_salaire', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('personnel', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('salaire', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('scolarite', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('taux', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });

        Schema::table('verrou_note', function(Blueprint $table)
        {
            $table->dropTimestamps();
        });
	}

}
