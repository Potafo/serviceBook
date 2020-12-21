<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToVendorStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vendor_status', function(Blueprint $table)
		{
			$table->foreign('status_id', 'fk_vendorstatus_status_status_id')->references('id')->on('status')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('vendor_id', 'fk_vendorstatus_vendor_vendor_id')->references('id')->on('vendor')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('vendor_status', function(Blueprint $table)
		{
			$table->dropForeign('fk_vendorstatus_status_status_id');
			$table->dropForeign('fk_vendorstatus_vendor_vendor_id');
		});
	}

}
