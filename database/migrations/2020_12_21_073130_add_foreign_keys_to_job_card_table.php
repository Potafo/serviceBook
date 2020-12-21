<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToJobCardTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('job_card', function(Blueprint $table)
		{
			$table->foreign('customer_id', 'fk_job_card_customers_customerid')->references('id')->on('customers')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'fk_job_card_users_userid')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('vendor_id', 'fk_job_card_vendor_vendor_id')->references('id')->on('vendor')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('job_card', function(Blueprint $table)
		{
			$table->dropForeign('fk_job_card_customers_customerid');
			$table->dropForeign('fk_job_card_users_userid');
			$table->dropForeign('fk_job_card_vendor_vendor_id');
		});
	}

}
