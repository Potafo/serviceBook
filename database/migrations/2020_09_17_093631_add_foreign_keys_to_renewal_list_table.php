<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRenewalListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('renewal_list', function(Blueprint $table)
		{
			$table->foreign('package', 'package_renewal_id')->references('id')->on('package')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('vendor_id', 'vendor_renewal_id')->references('id')->on('vendor')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('renewal_list', function(Blueprint $table)
		{
			$table->dropForeign('package_renewal_id');
			$table->dropForeign('vendor_renewal_id');
		});
	}

}
