<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesExecutiveTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sales_executive', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 100);
			$table->string('mobile', 50)->nullable();
			$table->string('email', 100)->nullable();
			$table->char('status', 1)->nullable()->default('Y');
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('modified_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sales_executive');
	}

}
