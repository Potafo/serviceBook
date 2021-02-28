<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Jobcard;
use App\Cart;
use Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
class ClearController extends Controller
{
    public function cleartables($tables)
    {

        $vendor=DB::delete("TRUNCATE TABLE $tables ");
        //$token=Str::random(20);
        //$pass_super=Hash::make('superadmin@123');
       // DB::select('UPDATE `users` SET  `remember_token`="'.$token.'"   WHERE `email`="superadmin@gmail.com"');
        // $sql=DB::table('job_card')->get();
        // foreach($sql as $val)
        // {
        //     $jobcard = Jobcard::firstOrFail()->where('jobcard_number','=',$val->jobcard_number);
        //     $saved = $jobcard->delete($jobcard);
        // }
        // $sql=DB::table('cart')->get();
        // foreach($sql as $val)
        // {
        //     $jobcard = Cart::firstOrFail()->where('	jobcard_reference ','=',$val->jobcard_reference);
        //     $saved = $jobcard->delete($jobcard);
        // }
    }
}
