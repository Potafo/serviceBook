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
			$table->foreign('package', 'fk_renewallist_package_packageid')->references('id')->on('package')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('vendor_id', 'fk_renewallist_vendor_vendorid')->references('id')->on('vendor')->onUpdate('CASCADE')->onDelete('CASCADE');
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
			$table->dropForeign('fk_renewallist_package_packageid');
			$table->dropForeign('fk_renewallist_vendor_vendorid');
		});
	}

}
