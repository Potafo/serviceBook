<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       /* Schema::create('trigger', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });*/
        DB::unprepared("CREATE TRIGGER `insert_to_vendor` AFTER INSERT ON `users`
        FOR EACH ROW
            begin
                IF new.user_type ='3' THEN
                    insert into vendor(name,mail_id) values (new.name, new.email);
                ELSEIF new.user_type='4' THEN
                    insert into sales_executive(name,email) values (new.name, new.email);
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
