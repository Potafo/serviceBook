<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('package', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('type', 100)->nullable();
			$table->string('days', 50)->nullable();
			$table->char('status', 1)->nullable()->default('Y');
			$table->string('amount', 100)->nullable();
			$table->timestamp('created_at')->default(db::raw('current_timestamp'));
            $table->timestamp('updated_at')->default(db::raw('current_timestamp on update current_timestamp'));
            $table->engine = "innodb";
        });

        $data = [
            ['type'=>'Free Trial','days'=>'30', 'status'=> 'Y','amount'=>'0'],

        ];
        DB::table('package')->insert($data
        );
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('package');
	}

}
