<?php
namespace database\seeds;
use gesecole\niveau;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class filiereTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        niveau::insert(['nom'=>'COMMERCIALES ET GESTION','nom_anglais'=>'BUSINESS,FINANCE AND MANAGEMENT']);
        niveau::insert(['nom'=>'COMMUNICATION ET INFORMATION','nom_anglais'=>'COMMUNICATION AND INFORMATION']);
    }

}

