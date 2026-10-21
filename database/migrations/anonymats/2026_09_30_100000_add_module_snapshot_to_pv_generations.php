<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleSnapshotToPvGenerations extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pv_generations')) return;

        Schema::table('pv_generations', function (Blueprint $table) {
            if (!Schema::hasColumn('pv_generations', 'id_campus')) {
                $table->integer('id_campus')->unsigned()->nullable()->index();
            }
            if (!Schema::hasColumn('pv_generations', 'id_tutelle')) {
                $table->integer('id_tutelle')->unsigned()->nullable()->index();
            }
            if (!Schema::hasColumn('pv_generations', 'contenu_html')) {
                $table->longText('contenu_html')->nullable();
            }
            if (!Schema::hasColumn('pv_generations', 'contexte')) {
                $table->text('contexte')->nullable();
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('pv_generations')) return;

        Schema::table('pv_generations', function (Blueprint $table) {
            if (Schema::hasColumn('pv_generations', 'contexte')) {
                $table->dropColumn('contexte');
            }
            if (Schema::hasColumn('pv_generations', 'contenu_html')) {
                $table->dropColumn('contenu_html');
            }
            if (Schema::hasColumn('pv_generations', 'id_tutelle')) {
                $table->dropColumn('id_tutelle');
            }
            if (Schema::hasColumn('pv_generations', 'id_campus')) {
                $table->dropColumn('id_campus');
            }
        });
    }
}
