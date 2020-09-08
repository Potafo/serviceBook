<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Status;
use Response;

class StatusController extends Controller
{
    public function add_status(Request $request)
    {
        //`status`(`id`, `name`, `active`, `created_at`, `modified_at`)
        $savestatus=0;
        $service= new Status();
        $service->name               =$request['name'];
       $saved=$service->save();
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
