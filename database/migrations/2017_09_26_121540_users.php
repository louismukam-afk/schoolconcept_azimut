<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Users extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('fullname');
			$table->string('email')->nullable();
			$table->string('password');
			$table->string('remember_token')->nullable();
			$table->float('role')->default(20);
			$table->integer('active')->default(0);
			$table->integer('id_personnel')->default(0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users', function(Blueprint $table)
		{
			//
		});
	}

}
