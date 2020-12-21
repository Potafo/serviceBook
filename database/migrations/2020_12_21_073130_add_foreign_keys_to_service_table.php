<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('service', function(Blueprint $table)
		{
			$table->foreign('vendor_id', 'fk_service_vendorid_vendor_id')->references('id')->on('vendor')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('service', function(Blueprint $table)
		{
			$table->dropForeign('fk_service_vendorid_vendor_id');
		});
	}

}
