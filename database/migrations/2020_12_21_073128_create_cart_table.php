<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cart', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('jobcard_reference', 100)->index('jobcard_reference');
			$table->string('jobcard_number', 100)->index('jobcard_number');
			$table->integer('service_id')->index('service_id');
			$table->string('service_name', 200)->nullable();
			$table->string('actual_price', 100)->nullable();
			$table->string('price', 100)->nullable();
			$table->string('tax_percent', 100)->nullable();
			$table->string('tax_amount', 100)->nullable();
			$table->string('total_with_tax', 100)->nullable();
            $table->string('total_without_tax', 100)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->engine = "InnoDB";
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cart');
	}

}
