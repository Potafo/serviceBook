<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirebasetokenMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firebasetoken_master', function (Blueprint $table) {
            $table->integer('id', true);
			$table->string('user_token', 200)->nullable();
            $table->string('sl_no', 100)->nullable();
			$table->string('device', 100)->nullable();
            $table->string('device_id', 100)->nullable();
            $table->string('fb_token', 200)->nullable();
			$table->string('app_version', 100)->nullable();
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
        Schema::dropIfExists('firebasetoken_master');
    }
}
