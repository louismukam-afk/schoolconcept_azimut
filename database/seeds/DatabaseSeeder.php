<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

        $this->call('database\seeds\eleveTableSeeder');
        $this->call('database\seeds\personnelTableSeeder');
        $this->call('database\seeds\parametreTableSeeder');
        $this->call('database\seeds\cycleTableSeeder');
        $this->call('database\seeds\filiereTableSeeder');
        $this->call('database\seeds\classeTableSeeder');
        $this->call('database\seeds\scolariteTableSeeder');
        $this->call('database\seeds\nniveauTableSeeder');
        $this->call('database\seeds\matiereTableSeeder');
        $this->call('database\seeds\programmeTableSeeder');
        $this->call('database\seeds\salaireTableSeeder');
        $this->call('database\seeds\horaireTableSeeder');
        $this->call('database\seeds\userTableSeeder');
        $this->call('database\seeds\inscriptionTableSeeder');
        $this->call('database\seeds\noteTableSeeder');





    }


}
