<?php

use gesecole\Etudiant_user;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserForEtudiant extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$eleve=\gesecole\eleve::join('inscription','eleve.id','=','inscription.id_eleve')
            ->select('eleve.*', 'inscription.id as id_inscription')
            ->distinct()
            ->get();
		foreach ($eleve as $e)
        {

            $es=Etudiant_user::where('id_eleve','=',$e->id)->first();
            if ($es==null){
                $et = new Etudiant_user();
                $et->username =$e->matricule;
                $et->password =$e->matricule;
                $et->id_inscription =$e->id_inscription;
                $et->id_eleve = $e->id;
                $et->save();
            }

        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Etudiant_user::truncate();
	}

}
