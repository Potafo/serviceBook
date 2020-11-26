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
			$table->increments('id');
			$table->string('type', 50);
            $table->char('status', 1)->default('Y');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });


        $data = [
            ['type'=>'Product Service', 'status'=> 'Y'],
            ['type'=>'General Service', 'status'=> 'Y'],

        ];
        DB::table('service_type')->insert($data
        );
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
