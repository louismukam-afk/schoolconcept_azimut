<?php
namespace database\seeds;
use gesecole\parametres;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class parametreTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        parametres::insert([
            'nom'=>'nom_etablissement',
            'valeur'=>'Groupe scolaire bilingue les bertillets'
        ]);
        parametres::insert([
            'nom'=>'nom_directeur',
            'valeur'=>'Jean-Marie Luc Bertino'
        ]);
        parametres::insert([
            'nom'=>'annee_scolaire',
            'valeur'=>'2017-2018'
        ]);
        parametres::insert([
            'nom'=>'initiales_etablissement',
            'valeur'=>'GBB'
        ]);
        parametres::insert([
            'nom'=>'telephone',
            'valeur'=>'(+237) 6 85 95 41 15 - 23 25 41 23'
        ]);
        parametres::insert([
            'nom'=>'adresse',
            'valeur'=>'Nyom maetur'
        ]);
        parametres::insert([
            'nom'=>'web',
            'valeur'=>'lesbertillets.cm'
        ]);
        parametres::insert([
            'nom'=>'email',
            'valeur'=>'lesbertillets@gmail.com'
        ]);
        parametres::insert([
            'nom'=>'configuration',
            'valeur'=>'OK'
        ]);
    }

}

