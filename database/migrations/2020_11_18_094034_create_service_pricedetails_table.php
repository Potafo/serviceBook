<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicePricedetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_pricedetails', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('service_id');
            $table->string('actual_price',100);
            $table->string('offer_price',100)->nullable();
            $table->string('discount_percent',100)->nullable();
            $table->string('discount_amount',100)->nullable();
            $table->string('tax_sgst',100)->nullable();
            $table->string('tax_cgst',100)->nullable();
            $table->integer('changed_by');
            $table->date('date')->nullable();
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
        Schema::dropIfExists('service_pricedetails');
    }
}
