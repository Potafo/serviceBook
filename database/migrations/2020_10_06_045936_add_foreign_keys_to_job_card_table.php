<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToJobCardTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('job_card', function(Blueprint $table)
		{
			$table->foreign('product_id', 'product_job_id')->references('id')->on('products')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('vendor_id', 'vendor_job_id')->references('id')->on('vendor')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('job_card', function(Blueprint $table)
		{
			$table->dropForeign('product_job_id');
			$table->dropForeign('vendor_job_id');
		});
	}

}
