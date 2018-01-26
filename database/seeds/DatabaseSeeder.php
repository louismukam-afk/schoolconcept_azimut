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
        for ($i=0;$i<1000;$i++)
		 $this->call('database\seeds\eleveTableSeeder');

		for ($i=0;$i<30;$i++)
		 $this->call('database\seeds\personnelTableSeeder');
	}

}
