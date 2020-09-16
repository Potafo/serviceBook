<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobcard;
use Response;

class JobcardController extends Controller
{
    public function insert_jobcard(Request $request)
    {
        // `job_card`(`id`, `user_id`, `vendor_id`, `jobcard_number`, `product_id`, `date`, `created_at`, `modified_at`)
        $savestatus=0;
        $jobcard= new Jobcard();
        $jobcard->user_id               =$request['user_id'];
        $jobcard->vendor_id               =$request['vendor_id'];
        $jobcard->product_id               =$request['product_id'];
        $jobcard->jobcard_number               =$request['jobcard_number'];
        $jobcard->date               =date("Y-m-d");
        $saved=$jobcard->save();
        if ($saved) {
            $savestatus++;
        }
        if($savestatus>0){
            $status = 'success';
           }else {
            $status = 'fail';
           }

            $response_code = '200';
            return response::json(['status' =>$status,'response_code' =>$response_code]);

    }
}
