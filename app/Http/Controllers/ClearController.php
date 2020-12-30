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

        //$vendor=DB::delete("TRUNCATE TABLE $tables ");
        $sql=DB::table('job_card')->get();
        foreach($sql as $val)
        {
            $jobcard = Jobcard::firstOrFail()->where('jobcard_number','=',$val->jobcard_number);
            $saved = $jobcard->delete($jobcard);
        }

    }
}
