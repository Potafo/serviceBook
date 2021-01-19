<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableVendorModifyJoinedon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor', function (Blueprint $table) {
            //
            $table->dropColumn('joined_on');


        });
        Schema::table('vendor', function (Blueprint $table) {
            //

            $table->timestamp('joined_on')->default(DB::raw('current_timestamp'))->nullable();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor', function (Blueprint $table) {
            //
            $table->dateTime('joined_on')->default(null)->change();

        });
    }
}
