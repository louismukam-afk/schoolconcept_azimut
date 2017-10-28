<?php
namespace database\seeds;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class eleveTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \gesecole\eleve::insert(
            ['nom'=>str_random(6).' '.str_random(7).' '.str_random(6),
                'date_naiss'=>random_int(1990,2000).'-'.random_int(1,12).'-'.random_int(1,31),
                'lieu_naiss'=>str_random(10),
                'sexe'=>$this->makeSex(random_int(0,1)),
                'nationalite'=>str_random(8),
                'quartier'=>str_random(7)
        ]
        );
    }

    public function makeSex($n){
        if ($n==0)
            return 'M';
        else
            return 'F';
    }

}
