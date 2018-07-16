<?php
namespace database\seeds;
use gesecole\cycle;
use gesecole\inscription;
use gesecole\niveau;
use gesecole\NNiveau;
use gesecole\payement;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class inscriptionTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        $n=sizeof($this->date);
        $m=sizeof($this->montant);
        for ($i=1;$i<=300;$i++)
        {
            $nn=random_int(1,5);
            $c=NNiveau::find($nn);
            inscription::insert(
                [
                    'date_inscription'=>$this->date[random_int(0,$n-1)],
                    'id_eleve'=>$i,
                    'annee_scolaire'=>'2017-2018',
                    'id_classe'=>random_int(1,6),
                    'id_cycle'=>$c->id_cycle,
                    'montant'=>50000,
                    'id_nniveau'=>$c->id

                ]
            );

            payement::insert(
                [
                    'id_eleve'=>$i,
                    'annee_scolaire'=>'2017-2018',
                    'date_payement'=>$this->date[random_int(0,$n-1)],
                    'montant'=>50000,
                    'Motif'=>'Inscription',
                    'numero'=>'00000'.$i
                ]);

            payement::insert(
                [
                    'id_eleve'=>$i,
                    'annee_scolaire'=>'2017-2018',
                    'date_payement'=>$this->date[random_int(0,$n-1)],
                    'montant'=>$this->montant[random_int(0,$m-1)],
                    'Motif'=>'Avance pension',
                    'numero'=>'PA0000'.(400+$i)
                ]);
        }

    }

    protected $date=
        [
            '2018-01-01',
            '2018-03-02',
            '2018-04-01',
            '2018-03-02',
            '2018-01-03',
            '2018-07-04',
            '2018-07-05',
            '2018-06-05',
            '2018-05-05',
            '2018-07-07',
            '2018-04-09',
            '2018-03-15',
            '2018-02-23',
            '2018-02-28',
            '2018-02-01',
            '2018-05-11',
            '2018-07-13',
            '2018-06-22',
            '2018-0-24',
            '2018-01-11',
            '2018-01-17',
        ];
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
        45000,
        120000,
        200000,
        55000,
        130000,
        150000,
        160000,
        180000


    ];
}


