<?php
namespace database\seeds;
use gesecole\personnel;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class personnelTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       personnel::insert(
            [
                'nom'=>str_random(6).' '.str_random(7).' '.str_random(6),
                'date_naiss'=>random_int(1990,2000).'-'.random_int(1,12).'-'.random_int(1,31),
                'lieu_naiss'=>str_random(10),
                'sexe'=>$this->makeSex(random_int(0,1)),
                'diplome'=>str_random(7),
                'date_entree'=>random_int(1990,2000).'-'.random_int(1,12).'-'.random_int(1,31),
                'tel1'=>str_random(7),
                'tel2'=>str_random(7),
                'adresse'=>str_random(7),
                'email'=>str_random(7).'@gmail.com',
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
