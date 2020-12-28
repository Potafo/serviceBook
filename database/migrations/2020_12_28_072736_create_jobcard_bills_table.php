<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobcardBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobcard_bills', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('jobcard_number', 100)->index('jobcard_number');
            $table->string('bill_amount', 100)->nullable();
            $table->string('received_amount', 100)->nullable();
            $table->string('discount_amount', 100)->nullable();
            $table->string('payment_mode', 100)->nullable()->default('CASH');
            $table->integer('vendor_status')->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->engine = "InnoDB";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobcard_bills');
    }
}
