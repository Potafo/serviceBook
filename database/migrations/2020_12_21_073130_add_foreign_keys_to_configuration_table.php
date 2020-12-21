<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToConfigurationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('configuration', function(Blueprint $table)
		{
			$table->foreign('type', 'fk_configuration_configurationtype_type')->references('id')->on('configuration_type')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('configuration', function(Blueprint $table)
		{
			$table->dropForeign('fk_configuration_configurationtype_type');
		});
	}

}
