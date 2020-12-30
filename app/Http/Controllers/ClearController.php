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

        $vendor=DB::delete("TRUNCATE TABLE $tables ");

    }
}
