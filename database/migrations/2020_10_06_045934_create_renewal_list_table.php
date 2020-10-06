<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenewalListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('renewal_list', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('vendor_id')->unsigned()->nullable()->index('vendor_renewal_id');
			$table->dateTime('renewal_date')->nullable();
			$table->integer('package')->unsigned()->nullable()->index('package_renewal_id');
            $table->string('amount_paid', 100)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('renewal_list');
	}

}
