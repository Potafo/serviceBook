<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorConfigurationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vendor_configuration', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('vendor_id')->index('vendor_id');
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
		Schema::drop('vendor_configuration');
	}

}
