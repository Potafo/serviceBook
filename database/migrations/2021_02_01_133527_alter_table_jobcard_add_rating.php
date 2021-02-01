<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableJobcardAddRating extends Migration
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
            $table->string('review',100)->nullable();
            $table->integer('star_rating')->nullable();
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
            $table->dropColumn('review');
            $table->dropColumn('star_rating');
        });
    }
}
