<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PayementSalaire extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payement_salaire', function(Blueprint $table)
		{
			$table->increments('id');
		    $table->integer('id_personnel');
		    $table->integer('id_user');
		    $table->float('montant');
		    $table->date('date_p');
		    $table->string('numero',20);
		    $table->integer('mois');
		    $table->integer('heure_realise');
		    $table->float('primes')->default(0);
		    $table->float('acomptes')->default(0);
		    $table->float('cnss')->default(0);
		    $table->float('cas_sociales')->default(0);
		    $table->float('assurances')->default(0);
		    $table->float('autre_retenues')->default(0);
		    $table->float('taux')->default(0);
		    $table->float('total')->default(0);
		    $table->float('total_retenu')->default(0);
		    $table->float('net_a_payer')->default(0);
		    $table->string('annee_scolaire',9);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('payement_salaire', function(Blueprint $table)
		{
			//
		});
	}

}
