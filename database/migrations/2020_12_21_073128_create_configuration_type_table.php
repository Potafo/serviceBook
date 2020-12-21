<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigurationTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('configuration_type', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 200);
			$table->timestamp('created_at')->default(db::raw('current_timestamp'));
            $table->timestamp('updated_at')->default(db::raw('current_timestamp on update current_timestamp'));
            $table->engine = "innodb";
        });

        $data = [
            ['name'=>'Admin'],
            ['name'=>'Common'],
            ['name'=>'Vendor'],
        ];
        DB::table('configuration_type')->insert($data);
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('configuration_type');
	}

}
