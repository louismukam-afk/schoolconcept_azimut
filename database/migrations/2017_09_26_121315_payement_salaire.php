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
		    $table->string('numero',20)->nullable();
		    $table->integer('mois');
		    $table->integer('heure_realise');
		    $table->float('primes')->default(0);
		    $table->float('acomptes')->default(0);
		    $table->float('cnps')->default(0);
		    $table->float('cas_sociaux')->default(0);
		    $table->float('assurances')->default(0);
            $table->float('IRPP')->default(0);
            $table->float('CAC')->default(0);
            $table->float('CFC')->default(0);
            $table->float('CRTV')->default(0);
            $table->float('pension')->default(0);
            $table->string('periode')->nullable();
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
