<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\Product;
use Response;
use DB;
use Session;

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

    public function services_view(Service $model)
    {
        $rows1=DB::table('service');
        $services=array();
        if(Session::get('logged_user_type') =='3')
        {
            $vendor_id=Session::get('logged_vendor_id');
            $services= $rows1->where('service.vendor_id','=',$vendor_id)
                ->paginate(5);
        }
        else if(Session::get('logged_user_type') =='1')
        {
            $services=$rows1->join('vendor', 'vendor.id', '=', 'products.vendor_id')
            ->select('service.*','vendor.name as vname')
            ->paginate(5);
        }

       return view('services.services',compact('services'));

    }
    public function services_add(Request $request)
    {
        $products=array();
        if(Session::get('logged_user_type') =='3')
        {
        $products=$this->product_list_query(Session::get('logged_vendor_id'));
        }
        return view('services.services_add', compact('products'));
    }
    public function product_list_query($vendorid)
    {
         //DB::enableQueryLog();
         $productlist=Product::select('id','name')
         ->where('vendor_id','=',$vendorid)
         ->get();
        // dd(DB::getQueryLog());
        return $productlist;
    }
}
