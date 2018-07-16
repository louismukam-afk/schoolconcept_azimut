<?php
namespace database\seeds;
use gesecole\classe;
use gesecole\niveau;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class classeTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        classe::insert(['code'=>'CO','nom'=>'Comptabilité','nom_anglais'=>'Accountancy','id_niveau'=>1,'capacite'=>500]);
        classe::insert(['code'=>'BAF','nom'=>'Banque et finance','nom_anglais'=>'Banking and finance','id_niveau'=>1,'capacite'=>500]);
        classe::insert(['code'=>'MA','nom'=>'Marketing','nom_anglais'=>'Marketing','id_niveau'=>1,'capacite'=>500]);
        classe::insert(['code'=>'PUB','nom'=>'Publicité','nom_anglais'=>'Publicity','id_niveau'=>2,'capacite'=>500]);
        classe::insert(['code'=>'COM','nom'=>'Communication et marketing','nom_anglais'=>'Communication and marketing','id_niveau'=>2,'capacite'=>500]);
        classe::insert(['code'=>'GLT','nom'=>'Gestion logistique et transport','nom_anglais'=>'Logistic and transport','id_niveau'=>1,'capacite'=>500]);

    }

}

