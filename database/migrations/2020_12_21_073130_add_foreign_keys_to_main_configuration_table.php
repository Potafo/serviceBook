<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMainConfigurationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('main_configuration', function(Blueprint $table)
		{
			$table->foreign('config_id', 'fk_mainconfiguration_configuration_configid')->references('id')->on('configuration')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('type', 'fk_mainconfiguration_configuration_type')->references('id')->on('configuration_type')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('main_configuration', function(Blueprint $table)
		{
			$table->dropForeign('fk_mainconfiguration_configuration_configid');
			$table->dropForeign('fk_mainconfiguration_configuration_type');
		});
	}

}
