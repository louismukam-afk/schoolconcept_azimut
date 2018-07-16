<?php
namespace database\seeds;
use gesecole\matiere;
use gesecole\niveau;
use gesecole\NNiveau;
use gesecole\salaire;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class salaireTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        $n=sizeof($this->montant);
        for ($i=0;$i<35;$i++)
            salaire::insert([
                'id_personnel'=>$i,
                'type'=>0,
                'montant'=>$this->montant[random_int(0,$n-1)]
            ]);

    }


    protected $montant=[
        50000,
        75000,
        60000,
        100000,
        110000,
        80000,
        85000,
        90000,
        95000,
        65000,
        70000,
        35000,
        45000


    ];

}

