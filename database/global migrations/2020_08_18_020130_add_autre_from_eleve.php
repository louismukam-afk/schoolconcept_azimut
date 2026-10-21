<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutreFromEleve  extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eleve', function(Blueprint $table)
		{
			$table->string('telephone_wathsapp_eleve')->default(0);
			$table->float('moyenne_generale')->default(0);
			$table->string('serie')->nullable();
			$table->string('localisation_ecole')->nullable();
			$table->string('source_financement')->nullable();
			$table->string('canal_decouvert')->nullable();
			$table->string('site_internet')->nullable();
			$table->string('abonnement')->nullable();
			$table->string('telephone_wathsapp_pere')->nullable();
			$table->string('telephone_wathsapp_mere')->nullable();
			$table->string('telephone_wathsapp_tuteur')->nullable();
			$table->string('telephone_wathsapp_autre')->nullable();
			$table->string('abonnement_valide')->nullable();
			$table->string('chaine')->nullable();
			$table->string('numero_conseille')->nullable();
			
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eleve', function(Blueprint $table)
		{
			$table->dropColumn('telephone_wathsapp_eleve');
			$table->dropColumn('moyenne_generale');
			$table->dropColumn('serie');
			$table->dropColumn('localisation_ecole');
			$table->dropColumn('source_financement');
			$table->dropColumn('canal_decouvert');
			$table->dropColumn('site_internet');
			$table->dropColumn('abonnement');
			$table->dropColumn('telephone_wathsapp_pere');
			$table->dropColumn('telephone_wathsapp_mere');
			$table->dropColumn('telephone_wathsapp_tuteur');
			$table->dropColumn('telephone_wathsapp_autre');
			$table->dropColumn('abonnement_valide');
			$table->dropColumn('chaine');
			$table->dropColumn('numero_conseille');
		});
	}

}
