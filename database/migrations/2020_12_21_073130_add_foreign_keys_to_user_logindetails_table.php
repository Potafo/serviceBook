<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToUserLogindetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_logindetails', function(Blueprint $table)
		{
			$table->foreign('userid', 'fk_userlogindetails_users_userid')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_logindetails', function(Blueprint $table)
		{
			$table->dropForeign('fk_userlogindetails_users_userid');
		});
	}

}
