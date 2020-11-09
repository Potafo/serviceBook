<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStatusTableFieldRename extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('service_type', function(Blueprint $table) {
        //     $table->renameColumn('type', 'name');
        // });
        DB::statement("ALTER TABLE `service_type` CHANGE `type` `name` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('service_type', function(Blueprint $table) {
        //     $table->renameColumn('name', 'type');
        // });
        DB::statement("ALTER TABLE `service_type` CHANGE `name` `type` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");

    }
}
