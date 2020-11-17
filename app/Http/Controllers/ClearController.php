<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Jobcard;

class ClearController extends Controller
{
    public function cleartables($tables)
    {
        $vendor=DB::delete("TRUNCATE TABLE $tables ");
        //$vendor=DB::delete("TRUNCATE TABLE `job_card`");
        //DB::raw("TRUNCATE TABLE `job_card`");
    }
}
