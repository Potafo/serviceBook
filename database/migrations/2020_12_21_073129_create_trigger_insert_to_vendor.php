<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTriggerInsertToVendor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE TRIGGER `insert_to_vendor` AFTER INSERT ON `users`
        FOR EACH ROW
            begin
                IF new.user_type ='3' THEN
                    insert into vendor(name,user_id,mail_id,current_package,category,type) values (new.name,new.id, new.email,'1','1','1');
                ELSEIF new.user_type='4' THEN
                    insert into sales_executive(name,user_id,email) values (new.name,new.id, new.email);
                end IF;
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
        //Schema::dropIfExists('trigger');
        DB::unprepared('DROP TRIGGER `insert_to_vendor`');
    }
}
