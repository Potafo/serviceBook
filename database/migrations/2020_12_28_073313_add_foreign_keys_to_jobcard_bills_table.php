<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToJobcardBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobcard_bills', function (Blueprint $table) {
            $table->foreign('jobcard_number', 'fk_jobcardbills_jobcard_jobcardnumber')->references('jobcard_number')->on('job_card')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
        });
    }
}
