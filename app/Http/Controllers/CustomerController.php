<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use Response;
use Session;
class CustomerController extends Controller
{
   public function add_customer(Request $request)
    {
        //`customers`(`id`, `name`, `contact_number`, `vendor_id`)
        $savestatus=0;
        $customer= new Customer();
        $customer->name              =$request['name'];
        $customer->contact_number    =$request['contact_number'];
        $customer->vendor_id         =$request['vendor_id'];
        $saved=$customer->save();
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
