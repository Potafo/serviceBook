<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterJobcardDelServiceProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_card', function (Blueprint $table) {
            $table->dropForeign('product_job_id');
        });

        DB::statement("ALTER TABLE `job_card` DROP `product_id`, DROP `generalservice`, DROP `productservice`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('job_card', function (Blueprint $table) {
            $table->integer('product_id')->unsigned()->nullable();
            $table->string('generalservice',200)->nullable();
            $table->string('productservice',200)->nullable();
        });
    }
}
