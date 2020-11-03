<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTriggerStatusAutoInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE TRIGGER `add_default_status` AFTER INSERT ON `vendor`
        FOR EACH ROW
            begin
                insert into status( `vendor_id`, `name`, `notification`, `display_order`) values (new.id, 'Received','N','1');
                insert into status( `vendor_id`, `name`, `notification`, `display_order`) values (new.id, 'Delivered','N','1');
            end
            ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `add_default_status`');
    }
}
