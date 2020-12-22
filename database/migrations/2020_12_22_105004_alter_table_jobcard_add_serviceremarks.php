<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableJobcardAddServiceremarks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_card', function (Blueprint $table) {
            //
            $table->string('service_remarks',200)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_card', function (Blueprint $table) {
            //
            $table->dropColumn('service_remarks');

        });
    }
}
