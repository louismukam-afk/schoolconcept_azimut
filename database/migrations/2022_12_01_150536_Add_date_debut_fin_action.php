<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateDebutFinAction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acces_eleves', function (Blueprint $table) {
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->double('montant')->default(0);
            $table->integer('status_etudiant')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acces_eleves');
    }
}
