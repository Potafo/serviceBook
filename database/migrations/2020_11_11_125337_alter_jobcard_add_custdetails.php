<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterJobcardAddCustdetails extends Migration
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
            $table->string('name',200)->nullable();
            $table->string('mobile',200)->nullable();
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
            $table->dropColumn('name');
            $table->dropColumn('mobile');
        });
    }
}
