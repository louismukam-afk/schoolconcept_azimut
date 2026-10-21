<?php
namespace database\seeds;
use gesecole\classe;
use gesecole\niveau;
use gesecole\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class userTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
      User::insert([
          'name'=>'tamanois',
          'fullname'=>'tamanois',
          'password'=>'$2y$10$SswXQVoERqPmUHzdMGlky.o7nTw1ySt3J4ZyHBi8nd/mAdUX2rt0q',
          'role'=>1,
          'active'=>1,
          'created_at'=>'2017-09-20 14:35:15',
          'updated_at'=>'2017-09-20 14:35:15'
          ]);
    }

}

