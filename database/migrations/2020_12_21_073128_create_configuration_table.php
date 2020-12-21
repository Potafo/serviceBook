<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigurationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('configuration', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('type')->index('type');
			$table->string('config_name', 200);
			$table->char('status', 1)->nullable()->default('N');
			$table->char('page_view', 1)->nullable()->default('N');
			$table->string('input_type', 50);
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
		Schema::drop('configuration');
	}

}
