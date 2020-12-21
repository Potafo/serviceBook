<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToServicePricedetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('service_pricedetails', function(Blueprint $table)
		{
			$table->foreign('service_id', 'fk_servicepricedetails_service_serviceid')->references('id')->on('service')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('service_pricedetails', function(Blueprint $table)
		{
			$table->dropForeign('fk_servicepricedetails_service_serviceid');
		});
	}

}
