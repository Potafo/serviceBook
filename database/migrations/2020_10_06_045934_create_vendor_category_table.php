<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vendor_category', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 100)->nullable();
            $table->char('status', 1)->nullable()->default('Y');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        $data = [
            ['name'=>'Category1', 'status'=> 'Y'],
            ['name'=>'Category2', 'status'=> 'Y'],
        ];
        DB::table('vendor_category')->insert($data
        );
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vendor_category');
	}

}
