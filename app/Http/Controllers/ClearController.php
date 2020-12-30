<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Jobcard;
use App\Cart;
use Session;
class ClearController extends Controller
{
    public function cleartables($tables)
    {

        $vendor=DB::delete("TRUNCATE TABLE $tables ");
        // $sql=DB::table('job_card')->get();
        // foreach($sql as $val)
        // {
        //     $jobcard = Jobcard::firstOrFail()->where('jobcard_number','=',$val->jobcard_number);
        //     $saved = $jobcard->delete($jobcard);
        // }
        $sql=DB::table('cart')->get();
        foreach($sql as $val)
        {
            $jobcard = Cart::firstOrFail()->where('	jobcard_reference ','=',$val->jobcard_reference);
            $saved = $jobcard->delete($jobcard);
        }
    }
}
