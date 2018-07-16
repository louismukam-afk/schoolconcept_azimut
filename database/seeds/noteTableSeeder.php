<?php
namespace database\seeds;

use gesecole\note;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class noteTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {



        for ($i=1;$i<=300;$i++)
        {
            for ($j=1;$j<=15;$j++)
            {
                note::insert(
                    [
                        'id_matiere'=>$j,
                        'id_eleve'=>$i,
                        'sequence'=>1,
                        'note'=>random_int(10,29),
                        'annee_scolaire'=>'2017-2018'

                    ]
                );
                note::insert(
                    [
                        'id_matiere'=>$j,
                        'id_eleve'=>$i,
                        'sequence'=>2,
                        'note'=>random_int(10,29),
                        'annee_scolaire'=>'2017-2018'

                    ]
                );
                note::insert(
                    [
                        'id_matiere'=>$j,
                        'id_eleve'=>$i,
                        'sequence'=>3,
                        'note'=>random_int(23,68),
                        'annee_scolaire'=>'2017-2018'

                    ]
                );
                note::insert(
                    [
                        'id_matiere'=>$j,
                        'id_eleve'=>$i,
                        'sequence'=>4,
                        'note'=>random_int(23,68),
                        'annee_scolaire'=>'2017-2018'

                    ]
                );
            }

        }

    }


}


