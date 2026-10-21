<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bourses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_eleve');
            $table->string('annee_scolaire');
            $table->double('montant');
            $table->timestamps();

            $table->unique(['id_eleve', 'annee_scolaire'], 'unique__id_eleve__annee_scolaire');
            $table->foreign('id_eleve')->references('id')->on('eleve')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bourses');
    }
}
