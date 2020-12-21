<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('service_type', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 50);
			$table->char('status', 1)->default('Y');
			$table->string('table_connected', 100)->nullable();
			$table->integer('service_category');
			$table->timestamp('created_at')->default(db::raw('current_timestamp'));
            $table->timestamp('updated_at')->default(db::raw('current_timestamp on update current_timestamp'));
            $table->engine = "innodb";
        });

        $data = [
            ['name'=>'Products', 'status'=> 'Y','table_connected'=>'products','service_category'=>'1'],
            ['name'=>'General', 'status'=> 'Y','service_category'=>'1'],
            ['name'=>'Parts', 'status'=> 'Y','service_category'=>'2'],
        ];
        DB::table('service_type')->insert($data);
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('service_type');
	}

}
