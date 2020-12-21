<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusChangeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('status_change', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('jobcard_number', 100)->index('jobcard_number');
			$table->integer('from_status')->nullable();
			$table->integer('to_status')->nullable();
			$table->integer('change_by')->nullable();
			$table->date('date')->nullable();
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
		Schema::drop('status_change');
	}

}
