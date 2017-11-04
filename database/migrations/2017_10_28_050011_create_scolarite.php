<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScolarite extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('scolarite', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('id_cycle');
            $table->float('tranche1')->default(0);
            $table->float('tranche2')->default(0);
            $table->float('tranche3')->default(0);
            $table->float('tranche4')->default(0);
            $table->float('tranche5')->default(0);
            $table->float('total')->default(0);
            $table->integer('id_niveau');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scolarite', function(Blueprint $table)
        {
            //
        });
    }

}
