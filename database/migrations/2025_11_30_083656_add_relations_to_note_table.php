<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationsToNoteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::table('note', function (Blueprint $table) {

            // Classe de l'élève au moment de la note
            $table->integer('id_classe')
                ->nullable()
                ->after('id_eleve');

            // Lien vers la ligne classe_matiere (classe + matière + coef + type…)
            $table->integer('id_classe_matiere')
                ->nullable()
                ->after('id_classe');

            // Lien vers la session (cc1, sn1, cc2, etc.)
            $table->integer('id_session')
                ->nullable()
                ->after('id_classe_matiere');

            // Index
            $table->index('id_classe');
            $table->index('id_classe_matiere');
            $table->index('id_session');

            // IMPORTANT : Foreign keys retirées pour éviter les erreurs
            // Tu les remettras après avoir rempli les valeurs NULL
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('note', function (Blueprint $table) {

            // Suppression des index
            $table->dropIndex(['id_classe']);
            $table->dropIndex(['id_classe_matiere']);
            $table->dropIndex(['id_session']);

            // Suppression des colonnes
            $table->dropColumn('id_classe');
            $table->dropColumn('id_classe_matiere');
            $table->dropColumn('id_session');
        });
    }
}
