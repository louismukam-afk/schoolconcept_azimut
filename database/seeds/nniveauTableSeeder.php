<?php
namespace database\seeds;
use gesecole\niveau;
use gesecole\NNiveau;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class nniveauTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
       NNiveau::insert(['nom'=>'BTS-I','id_cycle'=>1]);
       NNiveau::insert(['nom'=>'BTS-II','id_cycle'=>1]);
       NNiveau::insert(['nom'=>'LICENCE-III','id_cycle'=>2]);
       NNiveau::insert(['nom'=>'MASTER-I','id_cycle'=>3]);
       NNiveau::insert(['nom'=>'MASTER-II','id_cycle'=>3]);

    }

}

