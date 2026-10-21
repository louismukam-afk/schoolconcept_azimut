<?php
namespace database\seeds;
use gesecole\scolarite;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class scolariteTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        for($i=1;$i<=2;$i++){
            scolarite::insert(['id_cycle'=>1,'inscription'=>50000,'tranche1'=>60000,'tranche2'=>60000,'tranche3'=>60000,'tranche4'=>60000,'tranche5'=>60000,'total'=>300000,'id_niveau'=>$i]);
            scolarite::insert(['id_cycle'=>2,'inscription'=>70000,'tranche1'=>100000,'tranche2'=>100000,'tranche3'=>100000,'tranche4'=>100000,'tranche5'=>100000,'total'=>500000,'id_niveau'=>$i]);
            scolarite::insert(['id_cycle'=>3,'inscription'=>90000,'tranche1'=>160000,'tranche2'=>160000,'tranche3'=>160000,'tranche4'=>160000,'tranche5'=>160000,'total'=>800000,'id_niveau'=>$i]);


        }


    }

}

