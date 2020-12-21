<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vendor_status', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('status_id')->index('status_id');
			$table->char('active', 1)->default('Y');
			$table->integer('vendor_id')->index('vendor_id');
			$table->char('send_sms', 1)->nullable()->default('N');
			$table->char('send_email', 1)->nullable()->default('N');
			$table->integer('display_order')->nullable();
            $table->integer('ending_status')->nullable()->default(0);
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
		Schema::drop('vendor_status');
	}

}
