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
use App\AppConfiguration;
use App\ConfigurationType;
use App\Jobcard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    protected $paginate;
    protected $product_serv_type;
    protected $appconfig;
    public function __construct()
    {
        $configurations = MainConfiguration::select('main_configuration.*')
            ->where('name', '=', 'mobile_paginate')->get();
        $this->paginate = $configurations[0]->value;

        $servicetype = ServiceType::select('service_type.*')
            ->where('name', '=', 'Products')->get();
        $this->product_serv_type = $servicetype[0]->id;


        $appconfigurations = ConfigurationType::select('configuration_type.*')
            ->where('name', '=', 'App')->get();
        $this->appconfig = $appconfigurations[0]->id;
    }
    public function login(Request $request)
    {
        $credentials = array(
            'email'    => $request['username'],
            'password' => $request['password'],
        );
        // Authenticate the user
        if (Auth::attempt($credentials)) {
            $login = DB::table('users')
                ->where('email', '=', $request['username'])
                ->select('users.*')
                ->get();
            $timezone = 'ASIA/KOLKATA';
            $date = new DateTime('now', new DateTimeZone($timezone));
            $userlogin = new UserLogin;
            $userlogin->userid = $login[0]->id;
            $userlogin->login_time = $date;
            $userlogin->save();

            return response()->json(['status' => "success", 'message' => "login success", 'user_token' => $login[0]->remember_token]);
        } else {
            return response()->json(['status' => "Failed", 'message' => "login failed", 'user_token' => ""]);
        }
    }
    ///*********************************** PRODUCTS ************************************************** */
    public function add_products(Request $request)
    {
        $savestatus = 0;
        $product = new Product();
        $product->name               = $request['productname'];
        $product->vendor_id          = $request['vendor_id'];

        $saved = $product->save();
        if ($saved) {
            $savestatus++;
        }
        if ($savestatus > 0) {
            $status = 'success';
            $message = "sussessfully inserted";
        } else {
            $status = 'fail';
            $message = "failed to insert";
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function editProduct(Request $request)
    {
        $savestatus = 1;
        $data['name']           = $request['productname'];

        $vendor = Product::findOrFail($request['products_id']);
        $saved = $vendor->update($data);
        if ($saved) {
            $savestatus++;
        }
        if ($savestatus > 0) {
            $status = 'success';
            $message = "sussessfully updated";
        } else {
            $status = 'fail';
            $message = "failed to update";
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }
    public function deleteProduct(Request $request)
    {
        $savestatus = 1;
        $vendor = Product::findOrFail($request['products_id']);
        $saved = $vendor->delete();
        if ($saved) {
            $savestatus++;
        }
        if ($savestatus > 0) {
            $status = 'success';
            $message = "sussessfully deleted";
        } else {
            $status = 'fail';
            $message = "failed to delete";
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }
    public function getProductList(Request $request)
    {
        $paginate = $this->paginate;
        $offset = 0;
        if ($request['index'] > 1) {
            $offset = (intval($request['index']) - 1) * $paginate;
        }
        // DB::enableQueryLog();
        $rows1 = DB::table('products');
        $products = $rows1->join('vendor', 'vendor.id', '=', 'products.vendor_id')
            ->select('products.*', 'vendor.name as vname')
            ->where('products.name', 'LIKE', '%' . $request['searchkey'] . '%')
            ->where('vendor.id', '=', $request['vendor_id'])
            ->limit($paginate)->offset($offset)->get();
        // dd(DB::getQueryLog());
        if (count($products) > 0) {
            $status = 'success';
        } else {
            $status = 'fail';
        }
        return response()->json(['status' => $status, 'product_list' => $products]);
    }
    ///*********************************** SERVICE ************************************************** */

    public function add_service(Request $request)
    {
        $savestatus = 0;
        $service = new Service();
        $service->name               = $request['service_name'];
        $service->type               = $request['service_type'];
        $service->vendor_id          = $request['vendor_id'];


        if ($request['service_type'] == $this->product_serv_type) {
            $service->product_id         = $request['product_id'];
        }
        $saved = $service->save();
        if ($saved) {
            $savestatus++;
        }
        if ($savestatus > 0) {
            $status = 'success';
            $message = "sussessfully inserted";
        } else {
            $status = 'fail';
            $message = "failed to insert";
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }
    public function editService(Request $request)
    {
        $savestatus = 1;
        $data['name']           = $request['service_name'];

        $vendor = Service::findOrFail($request['service_id']);
        $saved = $vendor->update($data);
        if ($saved) {
            $savestatus++;
        }
        if ($savestatus > 0) {
            $status = 'success';
            $message = "sussessfully updated";
        } else {
            $status = 'fail';
            $message = "failed to update";
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }
    public function deleteService(Request $request)
    {
        $savestatus = 1;
        $vendor = Service::findOrFail($request['service_id']);
        $saved = $vendor->delete();
        if ($saved) {
            $savestatus++;
        }
        if ($savestatus > 0) {
            $status = 'success';
            $message = "sussessfully deleted";
        } else {
            $status = 'fail';
            $message = "failed to delete";
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }
    public function getServiceList(Request $request)
    {
        $paginate = $this->paginate;
        $offset = 0;
        if ($request['index'] > 1) {
            $offset = (intval($request['index']) - 1) * $paginate;
        }
        $rows1 = DB::table('service');
        $service = $rows1->where('vendor_id', '=', $request['vendor_id'])
            ->select('service.*')
            ->where('name', 'LIKE', '%' . $request['searchkey'] . '%');
        if (!empty($request->input('type'))) {
            $service = $rows1->where('type', '=', $request['type']);
        }
        $service = $rows1->limit($paginate)->offset($offset)->get();
        if (count($service) > 0) {
            $status = 'success';
        } else {
            $status = 'fail';
        }
        return response()->json(['status' => $status, 'service_list' => $service]);
    }

    ///*********************************** CUSTOMER ************************************************** */

    public function getCustomerList(Request $request)
    {
        $paginate = $this->paginate;
        $offset = 0;
        if ($request['index'] > 1) {
            $offset = (intval($request['index']) - 1) * $paginate;
        }
        $rows1 = DB::table('customers');
        $customer = $rows1->where('vendor_id', '=', $request['vendor_id'])
            ->select('customers.*')
            ->where('name', 'LIKE', '%' . $request['searchkey'] . '%');
        $customer = $rows1->limit($paginate)->offset($offset)->get();
        if (count($customer) > 0) {
            $status = 'success';
        } else {
            $status = 'fail';
        }
        return response()->json(['status' => $status, 'customer_list' => $customer]);
    }

    ///*********************************** VENDOR ************************************************** */

    public function getVendorStatusList(Request $request)
    {

        $rows1 = DB::table('vendor_status');
        $status_list = $rows1->where('vendor_id', '=', $request['vendor_id'])
            ->join('status', 'status.id', '=', 'vendor_status.status_id')
            ->select('status.id as id', 'status.name as name');
        $status_list = $rows1->get();
        if (count($status_list) > 0) {
            $status = 'success';
        } else {
            $status = 'fail';
        }
        return response()->json(['status' => $status, 'status_list' => $status_list]);
    }

    ///*********************************** GENERAL SETTINGS ************************************************** */

    public function generalSettingsApp(Request $request)
    {

        $configurations = AppConfiguration::select('app_configuration.*')
            ->where('type', '=', $this->appconfig)->get();

        if (count($configurations) > 0) {
            $status = 'success';
        } else {
            $status = 'fail';
        }
        return response()->json(['status' => $status, 'app_config' => $configurations]);
    }
    public function getVendorData(Request $request)
    {
        //DB::enableQueryLog();

        $paginate = $this->paginate;
        $offset = 0;
        if ($request['index'] > 1) {
            $offset = (intval($request['index']) - 1) * $paginate;
        }
        $rows1 = DB::table('vendor')
            ->join('package', 'package.id', '=', 'vendor.current_package')
            ->join('vendor_category', 'vendor_category.id', '=', 'vendor.category')
            ->join('vendor_type', 'vendor_type.id', '=', 'vendor.type')
            ->join('users', 'users.id', '=', 'vendor.user_id')
            ->select('vendor.id as vid', 'users.active', 'vendor.last_renewal_date', 'vendor.user_id as userid', 'vendor.name as vname', 'package.days', 'package.type as pname', 'vendor.joined_on', 'vendor.contact_number', 'vendor_category.name as vcategory', 'vendor_type.name as vtype')
            ->orderBy('vendor.name', 'ASC');
        if (!empty($request['searchkey'])) {
            $searchQuery =  $request['searchkey'];

            $vendor = $rows1->where(function ($q) use ($searchQuery) {
                $q->Where('vendor.name', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('package.type', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('vendor.contact_number', 'LIKE',  '%' . $searchQuery . '%')
                    //->orWhere('vendor_category.name', 'LIKE',  '%' .$searchQuery. '%')
                    ->orWhere('vendor_type.name', 'LIKE', '%' . $searchQuery . '%');
                // ->orWhere('status.name', 'LIKE',  '%' .$searchQuery. '%')
                // ->orWhere('jobcard_bills.received_amount', 'LIKE',  '%' .$searchQuery. '%');
            });
        }


        $vendor = $rows1->limit($paginate)->offset($offset)->get();
        //dd(DB::getQueryLog());
        if (count($vendor) > 0) {
            $status = 'success';
        } else {
            $status = 'fail';
        }
        return response()->json(['status' => $status, 'vendor_list' => $vendor]);
    }
    public function vendorid_from_token($token)
    {
        $vendor_id = DB::table('users')
            ->select('vendor.id')
            ->join('vendor', 'vendor.user_id', '=', 'users.id')
            ->where('users.remember_token', '=', $token)
            ->get();
        $vendor = $vendor_id[0]->id;
        return $vendor;
    }
    public function getAppDashbord(Request $request)
    {
        $vendor_id = $this->vendorid_from_token($request['token']);
        //dd(Str::random(20));
        //DB::enableQueryLog();
        $status_list = DB::table('vendor_status')
            ->select('status.name', 'status.id', 'vendor_status.ending_status')
            ->join('status', 'status.id', '=', 'vendor_status.status_id')
            ->where('vendor_status.vendor_id', '=', $vendor_id)
            ->where('vendor_status.active', '=', 'Y')
            ->get();
        //dd(DB::getQueryLog());

        $i = 0;
        $dashboard_list = array();
        foreach ($status_list as $value) {

            $dashboard_list[$i]['name'] = $value->name;
            $dashboard_list[$i]['id'] = $value->id;
            if ($value->ending_status == 0) { //DB::enableQueryLog();
                $status_change = DB::table('status_change')
                    ->select(DB::raw('count(*) as statuscount'))
                    ->where('status_change.to_status', '=', $value->id)
                    ->where('status_change.date', '=', date('Y-m-d'))
                    ->where('status_change.change_by', '=', $vendor_id)
                    ->get();

                //dd(DB::getQueryLog());
                $dashboard_list[$i]['count'] = $status_change[0]->statuscount;
            } elseif ($value->ending_status == 1) {
                $status_change = DB::table('status_change_history')
                    ->select(DB::raw('count(*) as statuscount'))
                    ->where('status_change_history.to_status', '=', $value->id)
                    ->where('status_change_history.date', '=', date('Y-m-d'))
                    ->where('status_change_history.change_by', '=', $vendor_id)
                    ->get();
                $dashboard_list[$i]['count'] = $status_change[0]->statuscount;
            }
            $i++;
        }
        $chart = array();
        $chart_test = array();
        foreach ($dashboard_list as $key => $value) {
            $chart_test[$value['name']] = $value['count'];
        }
        if (count($chart_test) > 0) {
            $status = 'success';
        } else {
            $status = 'fail';
        }
        return response()->json(['status' => $status, 'count_list' => $chart_test]);
    }

    public function getPendingservices(Request $request)
    {
        $paginate = $this->paginate;
        $offset = 0;
        if ($request['index'] > 1) {
            $offset = (intval($request['index']) - 1) * $paginate;
        }
        $vendor_id = $this->vendorid_from_token($request['token']);
        $status_list = DB::table('vendor_status')
            //->select('vendor_status.status_id')
            ->where('vendor_status.vendor_id', '=', $vendor_id)
            ->where('vendor_status.active', '=', 'Y')
            ->where('vendor_status.ending_status', '=', '1')
            ->pluck('vendor_status.status_id')->toArray();
        //print_r($status_list);dd();
        //   dd(DB::getQueryLog());
        $rows1 = Jobcard::leftjoin('products', 'products.id', '=', 'job_card.product_id')
            ->leftjoin('customers', 'customers.id', '=', 'job_card.customer_id')
            ->leftjoin('status', 'status.id', '=', 'job_card.current_status')
            ->select('customers.name as custname', 'customers.id as custid', 'customers.contact_number as custmobile', 'products.name as pdtname', 'job_card.jobcard_number', 'job_card.created_at', 'job_card.id as jobcard_id', 'status.name as statusname')
            ->orderBy('job_card.created_at', 'DESC')
            ->where('job_card.jobcard_number', 'not like', 'Temp-%')
            //->where('job_card.current_status',\DB::raw($status_list),">",\DB::raw("'0'"))
            ->whereNotIn('job_card.current_status', $status_list)
            ->where('job_card.vendor_id', '=', $vendor_id);
        if ($request['status'] != 'all') {
            $jobcard  = $rows1->where('job_card.current_status', '=', $request['status']);
        }

        if (!empty($request['searchkey'])) {
            $searchQuery =  $request['searchkey'];

            $jobcard = $rows1->where(function ($q) use ($searchQuery) {
                $q->Where('customers.name', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('customers.contact_number', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('products.name', 'LIKE',  '%' . $searchQuery . '%')
                    ->orWhere('job_card.jobcard_number', 'LIKE',  '%' . $searchQuery . '%')
                    ->orWhere('job_card.id', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('status.name', 'LIKE',  '%' . $searchQuery . '%');
                // ->orWhere('jobcard_bills.received_amount', 'LIKE',  '%' .$searchQuery. '%');
            });
        }

        $jobcard  = $rows1->limit($paginate)->offset($offset)->get();
        $i = 0;
        $timezone = 'ASIA/KOLKATA';
        $date = new DateTime('now', new DateTimeZone($timezone));
        $jobcard_list = array();
        foreach ($jobcard as $key => $value) {

            $jobcard_list[$i]["custname"] = $value->custname;
            $jobcard_list[$i]["custid"] = $value->custid;
            $jobcard_list[$i]["custmobile"] = $value->custmobile;
            $jobcard_list[$i]["pdtname"] = $value->pdtname;
            $jobcard_list[$i]["jobcard_number"] = $value->jobcard_number;
            $jobcard_list[$i]["jobcard_id"] = $value->jobcard_id;


            //$package_days_count=$value->days;
            $joined_date = date("Y-m-d", strtotime($value->created_at));
            $current_date = date("Y-m-d");
            $diff = (new DateTime($joined_date))->diff(new DateTime($current_date))->days;
            $pending = $diff; //intval($package_days_count) - intval($diff);
            if ($pending == 0) {
                $pending = "Today";
            } elseif ($pending == 1) {
                $pending = $pending . " Day";
            } elseif ($pending > 1) {
                $pending = $pending . " Days";
            }

            $jobcard_list[$i]["days"] = $pending;
            $jobcard_list[$i]["statusname"] = $value->statusname;


            $i++;
        }

        if (count($jobcard) > 0) {
            $status = 'success';
        } else {
            $status = 'fail';
        }
        return response()->json(['status' => $status, 'jobcard' => $jobcard_list]);
        //dd($jobcard);
    }
    public function getReport(Request $request)
    {
        $paginate = $this->paginate;
        $offset = 0;
        if ($request['index'] > 1) {
            $offset = (intval($request['index']) - 1) * $paginate;
        }
        $vendor_id = $this->vendorid_from_token($request['token']);

        $status_list = DB::table('vendor_status')
            ->where('vendor_status.vendor_id', '=', $vendor_id)
            ->where('vendor_status.active', '=', 'Y')
            ->where('vendor_status.ending_status', '=', '0')
            ->pluck('vendor_status.status_id')->toArray();
        $rows1 = Jobcard::leftjoin('customers', 'customers.id', '=', 'job_card.customer_id')
            ->leftjoin('jobcard_bills', 'jobcard_bills.jobcard_number', '=', 'job_card.jobcard_number')
            ->select('customers.name as custname', 'customers.id as custid', 'customers.contact_number as custmobile',  'job_card.jobcard_number', 'job_card.date as jobcard_date', 'job_card.id', 'jobcard_bills.received_amount', 'jobcard_bills.bill_amount', 'jobcard_bills.discount_amount', 'jobcard_bills.tax_amount')
            ->orderBy('job_card.created_at', 'DESC')
            ->where('job_card.jobcard_number', 'not like', 'Temp-%')
            ->whereNotIn('job_card.current_status', $status_list);

        if (!empty($request['fromdate']) && !empty($request['todate'])) {
            $dfrom = date("Y-m-d", strtotime($request['fromdate']));
            $dto = date("Y-m-d", strtotime($request['todate']));
            $rows1->whereBetween('job_card.date', [$dfrom, $dto]);
        }
        if (!empty($request['fromdate']) && empty($request['todate'])) {
            $dfrom = date("Y-m-d", strtotime($request['fromdate']));
            $dto = date("Y-m-d");
            $rows1->whereBetween('job_card.date', [$dfrom, $dto]);
        }
        if (empty($request['fromdate']) && !empty($request['todate'])) {
            $dfrom = date("Y-m-d", strtotime($request['todate']));
            $dto = date("Y-m-d", strtotime($request['todate']));
            $rows1->whereBetween('job_card.date', [$dfrom, $dto]);
        }
        if (!empty($request['searchkey'])) {
            $searchQuery =  $request['searchkey'];

            $jobcard = $rows1->where(function ($q) use ($searchQuery) {
                $q->Where('customers.name', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('customers.contact_number', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('job_card.jobcard_number', 'LIKE',  '%' . $searchQuery . '%')
                    ->orWhere('job_card.date', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('jobcard_bills.received_amount', 'LIKE',  '%' . $searchQuery . '%');
            });
        }
        $rows1->where('job_card.vendor_id', '=', $vendor_id);
        if ($request['status'] != 'all') {
            $jobcard  = $rows1->where('job_card.current_status', '=', $request['status']);
        }

        $jobcard = $rows1->paginate($paginate);
        $i = 0;
        $jobcard_list = array();
        $billamount = 0;
        $taxamount = 0;
        $discount = 0;
        $amountrecieved = 0;
        foreach ($jobcard as $key => $value) {

            $jobcard_list[$i]["custname"] = $value->custname;
            $jobcard_list[$i]["jobcard_date"] = $value->jobcard_date;
            $jobcard_list[$i]["custmobile"] = $value->custmobile;
            $jobcard_list[$i]["jobcard_number"] = $value->jobcard_number;

            $jobcard_list[$i]["bill_amount"] = $value->bill_amount;
            $jobcard_list[$i]["tax_amount"] = $value->tax_amount;
            $jobcard_list[$i]["discount_amount"] = $value->discount_amount;
            $jobcard_list[$i]["received_amount"] = $value->received_amount;

            $billamount = intval($billamount)  + intval($value->bill_amount);
            $taxamount = intval($taxamount)  + intval($value->tax_amount);
            $discount = intval($discount)  + intval($value->discount_amount);
            $amountrecieved = intval($amountrecieved)  + intval($value->received_amount);
        }
        $jobcard_total['billamount'] = $billamount;
        $jobcard_total['taxamount'] = $taxamount;
        $jobcard_total['discount'] = $discount;
        $jobcard_total['amountrecieved'] = $amountrecieved;

        if (count($jobcard) > 0) {
            $status = 'success';
        } else {
            $status = 'fail';
        }
        return response()->json(['status' => $status, 'jobcard' => $jobcard_list, 'jobcard_total' => $jobcard_total]);
    }
}
