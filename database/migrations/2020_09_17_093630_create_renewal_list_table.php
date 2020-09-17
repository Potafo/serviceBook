<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenewalListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('renewal_list', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('vendor_id')->nullable()->index('vendor_renewal_id');
			$table->dateTime('renewal_date')->nullable();
			$table->integer('package')->nullable()->index('package_renewal_id');
			$table->bigInteger('amount_paid')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('renewal_list');
	}

}
