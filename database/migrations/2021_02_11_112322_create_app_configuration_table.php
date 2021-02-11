<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('app_configuration', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('type')->index('type');
			$table->string('name', 100)->nullable();
			$table->string('value', 100)->nullable();
			$table->integer('config_id')->index('config_id');
			$table->timestamp('created_at')->default(DB::raw('current_timestamp'));
            $table->timestamp('updated_at')->default(DB::raw('current_timestamp on update current_timestamp'));
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
        Schema::drop('app_configuration');
    }
}
