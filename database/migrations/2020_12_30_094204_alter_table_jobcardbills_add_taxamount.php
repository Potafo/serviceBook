<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableJobcardbillsAddTaxamount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobcard_bills', function (Blueprint $table) {
            //
            $table->string('tax_amount',100)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobcard_bills', function (Blueprint $table) {
            //
            $table->dropColumn('tax_amount');

        });
    }
}
