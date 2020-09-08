<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use Response;
class ServiceController extends Controller
{
    //to add service
    //(`id`, `name`, `type`, `vendor_id`, `product_id`)
    public function add_service(Request $request)
    {
        $savestatus=0;
        $service= new Service();
        $service->name               =$request['name'];
        $service->type               =$request['type'];
        $service->product_id         =$request['product_id'];
        //$service->vendor_id          =$request['vendor_id']; //already in product
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

    /*private function validate_data($request, $id = null)
    {
        return $request->validate([
            'name' => 'required|unique:categories,name,' . $id,
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'sort_order' => 'integer',
        ]);
    }*/
}
