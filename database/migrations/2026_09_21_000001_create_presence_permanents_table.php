<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePresencePermanentsTable extends Migration {

    public function up()
    {
        if(!Schema::hasTable('presence_permanents')){
            Schema::create('presence_permanents', function(Blueprint $table)
            {
                $table->increments('id');
                $table->integer('id_biometrie_import')->nullable()->index();
                $table->integer('id_personnel')->index();
                $table->integer('id_emploi_permanent')->index();
                $table->integer('id_plage_horaire')->nullable()->index();
                $table->date('date_presence')->index();
                $table->integer('jour_semaine');
                $table->time('heure_debut_prevue')->nullable();
                $table->time('heure_fin_prevue')->nullable();
                $table->time('heure_debut_reelle')->nullable();
                $table->time('heure_fin_reelle')->nullable();
                $table->float('duree_prevue')->default(0);
                $table->float('duree_realisee')->default(0);
                $table->float('salaire_journalier')->default(0);
                $table->float('montant_du')->default(0);
                $table->float('penalite_montant')->default(0);
                $table->string('statut',30)->default('absent');
                $table->text('observation')->nullable();
                $table->integer('id_user')->default(0);
                $table->timestamps();
                $table->unique(['id_biometrie_import','id_emploi_permanent','date_presence'],'uniq_presence_perm_import_emploi_date');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('presence_permanents');
    }
}
