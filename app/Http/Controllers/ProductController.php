<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Response;

class ProductController extends Controller
{
    public function insert_products(Request $request)
    {
        //`products`(`id`, `name`, `image`, `vendor_id`, `created_at`, `modified_at`)
        $savestatus=0;
        $product= new Product();
        $product->name               =$request['name'];
        $product->image              =$request['image'];
        $product->vendor_id          =$request['vendor_id'];
        $saved=$product->save();
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
