<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobcardServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobcard_services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('jobcard_reference', 100)->nullable();
            $table->string('jobcard_number', 100)->nullable();
            $table->integer('product_id')->nullable();
            $table->string('generalservice',200)->nullable();
            $table->string('productservice',200)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobcard_services');
    }
}
