<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobCardTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('job_card', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->index('user_id');
			$table->integer('vendor_id')->index('vendor_id');
			$table->string('jobcard_number', 100)->index('jobcard_number');
			$table->date('date')->nullable();
			$table->integer('customer_id')->index('customer_id');
			$table->integer('product_id')->index('product_id');
			$table->string('remarks', 250)->nullable();
            $table->integer('current_status')->nullable();
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
		Schema::drop('job_card');
	}

}
