<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToVendorServicetypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vendor_servicetype', function(Blueprint $table)
		{
			$table->foreign('service_type', 'fk_vendorservicetype_servicetype_servicetype')->references('id')->on('service_type')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('vendor_id', 'fk_vendorservicetype_vendor_vendorid')->references('id')->on('vendor')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('service_category', 'fk_vendorservicetype_servicecategory_catid')->references('id')->on('service_category')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('vendor_servicetype', function(Blueprint $table)
		{
			$table->dropForeign('fk_vendorservicetype_servicetype_servicetype');
			$table->dropForeign('fk_vendorservicetype_vendor_vendorid');
			$table->dropForeign('fk_verdorservicetype_servicecategory_catid');
		});
	}

}
