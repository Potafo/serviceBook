<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_type', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type', 50)->nullable();
			$table->char('status', 1)->nullable()->default('Y');
			$table->dateTime('created_at')->nullable();
			$table->dateTime('modified_at')->nullable();
        });
        $data = [
            ['type'=>'Admin', 'status'=> 'Y'],
            ['type'=>'Staff', 'status'=> 'Y'],
            ['type'=>'Vendor', 'status'=> 'Y'],
            ['type'=>'Sales_executive', 'status'=> 'Y'],
        ];
        DB::table('user_type')->insert($data
        );
        /*DB::table('user_type')->insert(
            array(
                'type' => 'Admin',
                'Status' => 'Y'
            )
        );*/
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_type');
	}

}
