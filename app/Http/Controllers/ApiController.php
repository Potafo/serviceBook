<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Response;
use Session;
use DateTime;
use DateTimeZone;
use App\UserLogin;
use DB;
use App\Product;
use App\MainConfiguration;
use App\Service;
use App\ServiceType;
use Illuminate\Support\Facades\Auth;
class ApiController extends Controller
{
    protected $paginate;
    protected $product_serv_type;
    public function __construct()
    {
        $configurations=MainConfiguration::select('main_configuration.*')
        ->where('name','=','mobile_paginate')->get();
        $this->paginate = $configurations[0]->value;

        $servicetype=ServiceType::select('service_type.*')
        ->where('name','=','Products')->get();
        $this->product_serv_type=$servicetype[0]->id;
    }
    public function login(Request $request)
    {
       $credentials = array(
            'email'    => $request['username'],
            'password' => $request['password'],
        );
       // Authenticate the user
       if (Auth::attempt($credentials))
        {
            $login = DB::table('users')
            ->where('email', '=',$request['username'])
            ->select('users.*')
            ->get();
            $timezone = 'ASIA/KOLKATA';
                $date = new DateTime('now', new DateTimeZone($timezone));
                $userlogin=new UserLogin;
                $userlogin->userid =$login[0]->id;
                $userlogin->login_time =$date;
                $userlogin->save();

                return response()->json(['status' => "success",'message'=>"login success",'user_token'=>$login[0]->remember_token]);
            }else{
                return response()->json(['status' => "Failed",'message'=>"login failed",'user_token'=>""]);
            }
    }
    ///*********************************** PRODUCTS ************************************************** */
    public function add_products(Request $request)
    {
        $savestatus=0;
        $product= new Product();
        $product->name               =$request['productname'];
        $product->vendor_id          =$request['vendor_id'];

        $saved=$product->save();
        if ($saved) {
            $savestatus++;
        }
        if($savestatus>0){
            $status = 'success';
            $message="sussessfully inserted";
           }else {
            $status = 'fail';
            $message="failed to insert";
           }
           return response()->json(['status' => $status,'message'=>$message]);

    }

    public function editProduct(Request $request)
    {
        $savestatus=1;
        $data['name']           =$request['productname'];

            $vendor = Product::findOrFail($request['products_id']);
            $saved=$vendor->update($data);
            if ($saved) {
                $savestatus++;
            }
            if($savestatus>0){
                $status = 'success';
                $message="sussessfully updated";
               }else {
                $status = 'fail';
                $message="failed to update";
               }
               return response()->json(['status' => $status,'message'=>$message]);

    }
    public function deleteProduct(Request $request)
    {
        $savestatus=1;
            $vendor = Product::findOrFail($request['products_id']);
            $saved=$vendor->delete();
            if ($saved) {
                $savestatus++;
            }
            if($savestatus>0){
                $status = 'success';
                $message="sussessfully deleted";
               }else {
                $status = 'fail';
                $message="failed to delete";
               }
               return response()->json(['status' => $status,'message'=>$message]);

    }
    public function getProductList(Request $request)
    {
        $paginate = $this->paginate;
        $offset=0;
        if($request['index']>1)
        {
            $offset = (intval($request['index']) -1 ) * $paginate;
        }
       // DB::enableQueryLog();
        $rows1=DB::table('products');
        $products=$rows1->join('vendor', 'vendor.id', '=', 'products.vendor_id')
        ->select('products.*','vendor.name as vname')
        ->where('products.name','LIKE','%'.$request['searchkey'].'%')
        ->where('vendor.id','=', $request['vendor_id'])
        ->limit($paginate)->offset($offset)->get();
       // dd(DB::getQueryLog());
        if(count($products)>0)
        {
            $status = 'success';
        }else{
            $status = 'fail';
        }
        return response()->json(['status' => $status,'product_list'=>$products]);

    }
///*********************************** SERVICE ************************************************** */

    public function add_service(Request $request)
    {
        $savestatus=0;
        $service= new Service();
        $service->name               =$request['service_name'];
        $service->type               =$request['service_type'];
        $service->vendor_id          =$request['vendor_id'];


        if ($request['service_type']==$this->product_serv_type) {
            $service->product_id         =$request['product_id'];
        }
        $saved=$service->save();
        if ($saved) {
            $savestatus++;
        }
        if($savestatus>0){
            $status = 'success';
            $message="sussessfully inserted";
           }else {
            $status = 'fail';
            $message="failed to insert";
           }
           return response()->json(['status' => $status,'message'=>$message]);
    }
    public function editService(Request $request)
    {
        $savestatus=1;
        $data['name']           =$request['service_name'];

            $vendor = Service::findOrFail($request['service_id']);
            $saved=$vendor->update($data);
            if ($saved) {
                $savestatus++;
            }
            if($savestatus>0){
                $status = 'success';
                $message="sussessfully updated";
               }else {
                $status = 'fail';
                $message="failed to update";
               }
               return response()->json(['status' => $status,'message'=>$message]);

    }
    public function deleteService(Request $request)
    {
        $savestatus=1;
            $vendor = Service::findOrFail($request['service_id']);
            $saved=$vendor->delete();
            if ($saved) {
                $savestatus++;
            }
            if($savestatus>0){
                $status = 'success';
                $message="sussessfully deleted";
               }else {
                $status = 'fail';
                $message="failed to delete";
               }
               return response()->json(['status' => $status,'message'=>$message]);

    }
    public function getServiceList(Request $request)
    {
        $paginate = $this->paginate;
        $offset=0;
        if($request['index']>1)
        {
            $offset = (intval($request['index']) -1 ) * $paginate;
        }
        $rows1=DB::table('service');
        $service=$rows1->where('vendor_id','=',$request['vendor_id'])
        ->select('service.*')
        ->where('name','LIKE','%'.$request['searchkey'].'%');
        if (!empty($request->input('type'))) {
            $service = $rows1->where('type', '=',$request['type']);
        }
        $service = $rows1->limit($paginate)->offset($offset)->get();
        if(count($service)>0)
        {
            $status = 'success';
        }else{
            $status = 'fail';
        }
        return response()->json(['status' => $status,'service_list'=>$service]);

    }

    ///*********************************** CUSTOMER ************************************************** */

    public function getCustomerList(Request $request)
    {
        $paginate = $this->paginate;
        $offset=0;
        if($request['index']>1)
        {
            $offset = (intval($request['index']) -1 ) * $paginate;
        }
        $rows1=DB::table('customers');
        $customer=$rows1->where('vendor_id','=',$request['vendor_id'])
        ->select('customers.*')
        ->where('name','LIKE','%'.$request['searchkey'].'%');
        $customer = $rows1->limit($paginate)->offset($offset)->get();
        if(count($customer)>0)
        {
            $status = 'success';
        }else{
            $status = 'fail';
        }
        return response()->json(['status' => $status,'customer_list'=>$customer]);

    }

    ///*********************************** VENDOR ************************************************** */

    public function getVendorStatusList(Request $request)
    {

        $rows1=DB::table('vendor_status');
        $status_list=$rows1->where('vendor_id','=',$request['vendor_id'])
        ->join('status','status.id','=','vendor_status.status_id')
        ->select('status.id as id','status.name as name');
        $status_list = $rows1->get();
        if(count($status_list)>0)
        {
            $status = 'success';
        }else{
            $status = 'fail';
        }
        return response()->json(['status' => $status,'status_list'=>$status_list]);

    }
}
