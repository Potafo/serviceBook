<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vendor', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 200)->nullable();
			$table->string('address', 200)->nullable();
			$table->string('location_lat', 50)->nullable();
			$table->string('location_long', 50)->nullable();
			$table->string('location_maplink', 500)->nullable();
			$table->string('location_embed', 500)->nullable();
			$table->string('description', 200)->nullable();
			$table->string('website', 200)->nullable();
			$table->string('mail_id', 100)->nullable();
			$table->string('image', 200)->nullable();
			$table->string('contact_number', 200)->nullable();
			$table->string('refferal_by', 50)->nullable();
			$table->dateTime('joined_on')->nullable();
			$table->integer('first_package')->nullable();
			$table->dateTime('last_renewal_date')->nullable();
			$table->integer('current_package')->nullable();
			$table->string('digital_profile_status', 50)->nullable()->default('Active');
			$table->integer('category')->nullable();
			$table->integer('type')->nullable();
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
		Schema::drop('vendor');
	}

}