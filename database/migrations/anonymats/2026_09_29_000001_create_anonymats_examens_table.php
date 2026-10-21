<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnonymatsExamensTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('anonymats_examens')) {
            Schema::create('anonymats_examens', function (Blueprint $table) {
                $table->increments('id');
                $table->string('scope_key', 40)->unique();
                $table->string('code', 40)->index();
                $table->integer('rang')->unsigned();
                $table->integer('id_eleve')->unsigned()->index();
                $table->integer('id_classe')->unsigned()->index();
                $table->integer('id_cycle')->unsigned()->index();
                $table->integer('id_nniveau')->unsigned()->index();
                $table->integer('id_matiere')->unsigned()->default(0)->index();
                $table->integer('id_classe_matiere')->unsigned()->nullable()->index();
                $table->integer('id_campus')->unsigned()->nullable()->index();
                $table->integer('id_tutelle')->unsigned()->default(0)->index();
                $table->string('annee_scolaire', 20)->index();
                $table->string('semestre', 2)->index();
                $table->string('type_examen', 30)->index();
                $table->string('mode_generation', 10)->index();
                $table->integer('id_creator')->unsigned()->nullable();
                $table->integer('id_last_editor')->unsigned()->nullable();
                $table->timestamps();
                $table->unique(array('annee_scolaire', 'code'), 'anon_year_code_unique');
            });
        }

        if (Schema::hasTable('note') && !Schema::hasColumn('note', 'id_anonymat')) {
            Schema::table('note', function (Blueprint $table) {
                $table->integer('id_anonymat')->unsigned()->nullable()->index();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('note') && Schema::hasColumn('note', 'id_anonymat')) {
            Schema::table('note', function (Blueprint $table) {
                $table->dropIndex(array('id_anonymat'));
                $table->dropColumn('id_anonymat');
            });
        }

        Schema::dropIfExists('anonymats_examens');
    }
}
