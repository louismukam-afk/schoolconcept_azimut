<?php
namespace database\seeds;
use gesecole\cycle;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class cycleTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        cycle::insert(['nom'=>'BTS']);
        cycle::insert(['nom'=>'LICENCE']);
        cycle::insert(['nom'=>'MASTER']);
    }

}

