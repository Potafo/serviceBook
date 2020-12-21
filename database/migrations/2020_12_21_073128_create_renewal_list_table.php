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
			$table->integer('id', true);
			$table->integer('vendor_id')->index('vendor_renewal_id');
			$table->dateTime('renewal_date')->nullable();
			$table->integer('package')->index('package_renewal_id');
			$table->string('amount_paid', 100)->nullable();
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
		Schema::drop('renewal_list');
	}

}
