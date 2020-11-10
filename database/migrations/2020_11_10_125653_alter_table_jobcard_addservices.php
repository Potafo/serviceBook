<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableJobcardAddservices extends Migration
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
            $table->string('generalservice',200)->nullable();
            $table->string('productservice',200)->nullable();
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
            $table->dropColumn('generalservice');
            $table->dropColumn('productlservice');
        });
    }
}
