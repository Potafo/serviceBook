<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRefferalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_refferal', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->index('user_refferal_user_id_foreign');
			$table->string('refferal_code');
			$table->date('used_date');
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
		Schema::drop('user_refferal');
	}

}
