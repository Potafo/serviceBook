<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToVendorConfigurationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vendor_configuration', function(Blueprint $table)
		{
			$table->foreign('vendor_id', 'fk_vendorconfiguration_vendor_vendorid')->references('id')->on('vendor')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('vendor_configuration', function(Blueprint $table)
		{
			$table->dropForeign('fk_vendorconfiguration_vendor_vendorid');
		});
	}

}
