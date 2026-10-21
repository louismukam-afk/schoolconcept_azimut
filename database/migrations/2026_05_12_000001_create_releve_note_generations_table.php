<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReleveNoteGenerationsTable extends Migration {

    public function up()
    {
        Schema::create('releve_note_generations', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('type')->default('bts');
            $table->string('annee_academique');
            $table->integer('id_niveau')->index(); // filiere
            $table->integer('id_classe')->index();
            $table->integer('id_cycle')->index();
            $table->integer('id_nniveau')->index();
            $table->date('periode_debut')->nullable();
            $table->date('periode_fin')->nullable();
            $table->integer('nb_releves')->default(0);
            $table->string('hash_generation', 64)->index();
            $table->longText('contenu');
            $table->integer('id_creator')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('releve_note_generations');
    }
}
