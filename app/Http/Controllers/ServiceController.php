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
use App\Traits\ListQueryById;
use App\VendorServiceType;

class ServiceController extends Controller
{
    use ListQueryById;
    public function add_service(Request $request)
     {
        $savestatus=0;
        $service= new Service();
        $service->name               =$request['servicename'];
        $service->type               =$request['servicetype_list'];
        if($request['serv_type']=='products')
        $service->product_id         =$request['product_list'];
        if(Session::get('logged_user_type') =='3')
        {
            $service->vendor_id         =Session::get('logged_vendor_id');
        }else if(Session::get('logged_user_type') =='1')
        {
            $service->vendor_id         =$request['vendor_name'];
        }
        $saved=$service->save();

        $insertedId = $service->id;
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
        $rows1=DB::table('service')
        ->join('service_type', 'service_type.id', '=', 'service.type')
        ->leftJoin('products', 'products.id', '=', 'service.product_id');
        $services=array();
        if(Session::get('logged_user_type') =='3')
        {
            $vendor_id=Session::get('logged_vendor_id');
            $services= $rows1->where('service.vendor_id','=',$vendor_id)
                ->select('service.*','products.name as pdtname','service.name as sername','service_type.name as sname')
                ->paginate(Session::get('paginate'));
        }
        else if(Session::get('logged_user_type') =='1')
        {
            $services=$rows1->join('vendor', 'vendor.id', '=', 'service.vendor_id')
                ->select('service.*','products.name as pdtname','service.name as sername','service_type.name as sname','vendor.name as vname','products.name as pdtname')
                ->paginate(Session::get('paginate'));
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
        //`vendor_servicetype`(`id`, `service_type`, `vendor_id`, `status`, `deleted_at`, `created_at`,
        //`service_type`(`id`, `name`, `status`, `created_at`, `updated_
        //DB::enableQueryLog();
        $servicelist_vendor=DB::table('service_type')
        ->select('service_type.*')
        ->join('vendor_servicetype', 'service_type.id', '=', 'vendor_servicetype.service_type')
         ->where('vendor_servicetype.vendor_id','=',Session::get('logged_vendor_id'))
         ->where('vendor_servicetype.status','=','Y')
         ->paginate(Session::get('paginate'));
         //dd(DB::getQueryLog());
        return view('services.services_add', compact('products','servicelist_vendor'));
    }

    public function validate_data(Request $request)
    {
        if((Session::get('tax_enabled')=='Y'))
        {
            $validator = Validator::make($request->all(), [
                'servicetype_list' => 'required|string|max:50',
                'serviceprice' => 'required|string|max:50',
                'product_list' => 'required_if:servicetype_list,==,products|nullable|string|max:50',
                'servicename' => 'required|string|max:100',
                'servicesgst' => 'required|string|max:100',
                'servicecgst' => 'required|string|max:100',
            ], [
                'servicetype_list.required' => 'Service Type List is required',
               'serviceprice.required' => 'Service Price is required',
                'servicename.required' => 'Service Name is required',
                'servicesgst.required' => 'Service SGST is required',
                 'servicecgst.required' => 'Service CGST is required'
              ]);

        }else{
            $validator = Validator::make($request->all(), [
                'servicetype_list' => 'required|string|max:50',
                'serviceprice' => 'required|string|max:50',
                'product_list' => 'required_if:servicetype_list,==,1|nullable|string|max:50',
                'servicename' => 'required|string|max:100',
            ], [
                'servicetype_list.required' => 'Service Type List is required',
               'serviceprice.required' => 'Service Price is required',
                'servicename.required' => 'Service Name is required'
              ]);
        }



          return $validator;
    }
    public function insert(Request $request)
    {
        $validator=$this->validate_data($request);
        if($validator->fails()) {
            // $errors = $validator->errors();
            // return Redirect()->back()->with('errors',$errors)->withInput($request->all());
            return Response::json(['errors' => $validator->errors()]);
        }else {
           $this->add_service($request);
            return Redirect('services')->with('status', 'Services Successfully Added!');
            //return Response::json(['success' => 1]);
        }
    }
}
