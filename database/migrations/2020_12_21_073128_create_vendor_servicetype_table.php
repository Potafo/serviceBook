<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorServicetypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vendor_servicetype', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('service_type')->index('fk_vendorservicetype_servicetype_servicetype');
			$table->integer('vendor_id')->index('vendor_id');
			$table->char('status', 1)->default('N');
			$table->integer('service_category')->index('service_category');
			$table->softDeletes();
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
		Schema::drop('vendor_servicetype');
	}

}
