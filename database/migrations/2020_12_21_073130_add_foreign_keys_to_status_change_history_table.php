<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStatusChangeHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('status_change_history', function(Blueprint $table)
		{
			$table->foreign('jobcard_number', 'fk_statuschangehistory_cart_jobcardnumber')->references('jobcard_number')->on('cart')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('status_change_history', function(Blueprint $table)
		{
			$table->dropForeign('fk_statuschangehistory_cart_jobcardnumber');
		});
	}

}
