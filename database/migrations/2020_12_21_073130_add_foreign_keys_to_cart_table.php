<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCartTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cart', function(Blueprint $table)
		{
			$table->foreign('service_id', 'fk_cart_service_serviceid')->references('id')->on('service')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('cart', function(Blueprint $table)
		{
			$table->dropForeign('fk_cart_service_serviceid');
		});
	}

}
