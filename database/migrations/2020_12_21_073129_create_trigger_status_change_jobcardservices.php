<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTriggerStatusChangeJobcardservices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE TRIGGER `status_change_jobcardservices` AFTER INSERT ON `status_change`
        FOR EACH ROW
            BEGIN
                UPDATE job_card set current_status=new.to_status where jobcard_number=new.jobcard_number;
            END");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `status_change_jobcardservices`');
    }
}
