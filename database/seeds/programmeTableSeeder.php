<?php
namespace database\seeds;
use gesecole\classeMatiere;
use gesecole\matiere;
use gesecole\niveau;
use gesecole\NNiveau;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class programmeTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        for ($i=1;$i<=3;$i++)
            for ($j=1;$j<=6;$j++)
                for ($k=1;$k<=5;$k++)
                    for ($l=1;$l<=15;$l++)
                        classeMatiere::insert([
                            'id_cycle'=>$i,
                            'id_classe'=>$j,
                            'id_matiere'=>$l,
                            'coef'=>random_int(1,5),
                            'max'=>100,
                            'semestre'=>random_int(0,2),
                            'type'=>random_int(0,1),
                            'id_nniveau'=>$k

                        ]);
    }



}

