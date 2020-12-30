<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Jobcard;
use Session;
class ClearController extends Controller
{
    public function cleartables($tables)
    {
        DB::select("ALTER TABLE service_book.status_change_history DROP FOREIGN KEY fk_statuschangehistory_cart_jobcardnumber");
        $vendor=DB::delete("TRUNCATE TABLE $tables ");
        //$vendor=DB::delete("TRUNCATE TABLE `job_card`");
        //DB::raw("TRUNCATE TABLE `job_card`");
    }
}
