<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeScolariteField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scolarite', function (Blueprint $table) {
            $table->integer('type_scolarite')->default(0);
        });

        Schema::table('inscription', function (Blueprint $table) {
            $table->integer('type_scolarite')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scolarite', function (Blueprint $table) {
            $table->dropColumn('type_scolarite');
        });

        Schema::table('inscription', function (Blueprint $table) {
            $table->dropColumn('type_scolarite');
        });
    }
}
