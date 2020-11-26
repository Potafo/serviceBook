<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\Product;
use App\ServicePriceDetails;
use Response;
use DB;
use Session;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    //to add service
    //(`id`, `name`, `type`, `vendor_id`, `product_id`)
    public function add_service(Request $request)
     {//servicetype_list vendor_name product_list  servicename
        $savestatus=0;
        $service= new Service();
        $service->name               =$request['servicename'];
        $service->type               =$request['servicetype_list'];
        if($request['servicetype_list']=='1')
        $service->product_id         =$request['product_list'];
        //$service->vendor_id          =$request['vendor_id']; //already in product
        if(Session::get('logged_user_type') =='3')
        {//$request['servicetype_list'] ==1
            $service->vendor_id         =Session::get('logged_vendor_id');
        }else if(Session::get('logged_user_type') =='1')
        {
            $service->vendor_id         =$request['vendor_name'];
        }
        $saved=$service->save();

        //(`id`, `service_id`, `actual_price`, `offer_price`, `discount_percent`,
        // `discount_amount`, `tax_sgst`, `tax_cgst`, `changed_by`, `date`,
        $insertedId = $service->id;//serviceprice servicesgst servicecgst serviceoffer
        $serviceprice=new ServicePriceDetails();
        $serviceprice->service_id=$insertedId;
        $serviceprice->actual_price=$request['serviceprice'];
        $serviceprice->offer_price=$request['serviceoffer'];
        $serviceprice->tax_sgst=$request['servicesgst'];
        $serviceprice->tax_cgst=$request['servicecgst'];
        $serviceprice->changed_by=Session::get('logged_user_id');
        $serviceprice->date=date('Y-m-d');
        $saved1=$serviceprice->save();

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
        //DB::enableQueryLog();
        $rows1=DB::table('service')
        ->join('service_type', 'service_type.id', '=', 'service.type')
        ->leftJoin('products', 'products.id', '=', 'service.product_id');
        $services=array();
        if(Session::get('logged_user_type') =='3')
        {
            $vendor_id=Session::get('logged_vendor_id');
            $services= $rows1->where('service.vendor_id','=',$vendor_id)
                ->select('service.*','products.name as pdtname','service.name as sername','service_type.name as sname')
                ->paginate(5);
        }
        else if(Session::get('logged_user_type') =='1')
        {
            $services=$rows1->join('vendor', 'vendor.id', '=', 'service.vendor_id')
                ->select('service.*','products.name as pdtname','service.name as sername','service_type.name as sname','vendor.name as vname','products.name as pdtname')
                ->paginate(5);
        }
        //dd(DB::getQueryLog());
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
    public function validate_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'servicetype_list' => 'required|string|max:50',
            'serviceprice' => 'required|string|max:50',
            //'product_list' => 'required|string|max:50',
            'servicename' => 'required|string|max:100',
            'servicesgst' => 'required|string|max:100',
            'servicecgst' => 'required|string|max:100',
        ], [
            'servicetype_list.required' => 'Service Type List is required',
           'serviceprice.required' => 'Service Price is required',
            'servicename.required' => 'Service Name is required'
          ]);
          return $validator;
    }
    public function insert(Request $request)
    {
        $validator=$this->validate_data($request);
        if($validator->fails()) {
             $errors = $validator->errors();
             return Redirect()->back()->with('errors',$errors)->withInput($request->all());
        }else {
            $this->add_service($request);
            return Redirect('services')->with('status', 'Services Successfully Added!');
        }
    }
}
