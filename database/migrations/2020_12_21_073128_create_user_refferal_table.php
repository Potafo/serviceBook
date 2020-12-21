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
			$table->integer('id', true);
			$table->integer('user_id')->index('user_id');
			$table->string('refferal_code', 191);
			$table->date('used_date');
			$table->timestamp('created_at')->default(db::raw('current_timestamp'));
            $table->timestamp('updated_at')->default(db::raw('current_timestamp on update current_timestamp'));
            $table->engine = "innodb";
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
