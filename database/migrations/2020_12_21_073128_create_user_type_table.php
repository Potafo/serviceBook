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
			$table->integer('id', true);
			$table->string('type', 50)->nullable();
			$table->char('status', 1)->nullable()->default('Y');
			$table->timestamp('created_at')->default(db::raw('current_timestamp'));
            $table->timestamp('updated_at')->default(db::raw('current_timestamp on update current_timestamp'));
            $table->engine = "innodb";
        });
        $data = [
            ['type'=>'Admin', 'status'=> 'Y'],
            ['type'=>'Staff', 'status'=> 'Y'],
            ['type'=>'Vendor', 'status'=> 'Y'],
            ['type'=>'Sales_executive', 'status'=> 'Y'],
        ];
        DB::table('user_type')->insert($data);
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
