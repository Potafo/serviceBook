<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 191);
			$table->string('email', 191)->unique();
			$table->string('password', 191);
			$table->string('remember_token', 100)->nullable();
			$table->integer('user_type')->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->engine = "InnoDB";
        });

        $pass_super=Hash::make('superadmin@123');
        $rem_token=Str::random(20);
        $data = [
            ['name'=>'superadmin', 'email'=> 'superadmin@gmail.com','password'=>$pass_super, 'user_type'=> 1,'remember_token'=> $rem_token],
            //['name'=>'admin', 'email'=> 'admin@gmail.com','password'=>$pass_admin, 'user_type'=> 2],
        ];
        DB::table('users')->insert($data);
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
