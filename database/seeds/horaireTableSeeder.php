<?php
namespace database\seeds;
use gesecole\horaire;
use gesecole\matiere;
use gesecole\niveau;
use gesecole\NNiveau;
use gesecole\salaire;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class horaireTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {

    horaire::insert([
        'intervalle'=>'08h00-10h00',
        'type'=>0,
        'ordre'=>1
    ]);
        horaire::insert([
            'intervalle'=>'10h00-12h00',
            'type'=>0,
            'ordre'=>2
        ]);
        horaire::insert([
            'intervalle'=>'12h00-12h30',
            'type'=>1,
            'ordre'=>3
        ]);
        horaire::insert([
            'intervalle'=>'12h30-14h30',
            'type'=>0,
            'ordre'=>4
        ]);
        horaire::insert([
            'intervalle'=>'14h30-16h30',
            'type'=>0,
            'ordre'=>5
        ]);

    }




}

