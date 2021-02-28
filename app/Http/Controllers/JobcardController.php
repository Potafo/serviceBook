<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobcard;
use App\Vendor;
use Response;
use App\Product;
use App\Service;
use App\Cart;
use App\ServicePriceDetails;
use App\StatusChange;
use App\StatusChangeHistory;
use App\Customer;
use App\JobcardBills;
use App\Reviews;
use App\User;
use App\Exports\UsersExport;
use App\Exports\JobcardReport;
use App\Exports\JobcardReportExport;
use App\Status;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Traits\ListQueryById;
use Illuminate\Support\Facades\Mail;
//use Maatwebsite\Excel\Facades\Excel;
//use App\Page;

class JobcardController extends Controller
{
    use ListQueryById;
    public function insert_jobcard(Request $request)
    {
        //$table->engine = "InnoDB";
        $status_data=array();

        $savestatus = 0;
        $vendorid = '';
        if (Session::get('logged_user_type') == '3') {
            $vendorid = Session::get('logged_vendor_id');
        } else if (Session::get('logged_user_type') == '1') {
            $vendorid               = $request['vendor_name'];
        }
        $data['name']                   = $request['jobcard_name'];
        $data['contact_number']         = $request['jobcard_mobile'];
        $data['email']                  = $request['jobcard_email'];
        $jobcard = Customer::findOrFail(Session::get('customerid'));
        $saved = $jobcard->update($data);

        $referencechange=Jobcard::select('job_card.*')
        ->where('jobcard_number','LIKE',Session::get('jobcard_reference').'%')
        ->get();
        foreach ($referencechange as $value) {
            $jobcrd=Session::get('logged_vendor_shortcode') . mt_rand(1000000, 99999999);
            $data['jobcard_number']                  = $jobcrd;
            $jobcard = Jobcard::findOrFail($value->id);
            $saved = $jobcard->update($data);

            $data1['jobcard_reference']                  = $jobcrd;
            $cart=Cart::firstOrFail()->where('jobcard_reference', $value->jobcard_number);
            $saved = $cart->update($data1);

            $data2['jobcard_number']                  = $jobcrd;
            $cart=StatusChange::firstOrFail()->where('jobcard_number', $value->jobcard_number);
            $saved = $cart->update($data2);

            $data3['jobcard_number']                  = $jobcrd;
            $cart=StatusChangeHistory::firstOrFail()->where('jobcard_number', $value->jobcard_number);
            $saved = $cart->update($data3);


        /***************Email Sending Starts **************************************/

        $statuslist = DB::table('vendor_status')
        ->select('vendor_status.status_id as id')
        ->where('vendor_status.vendor_id', '=', Session::get('logged_vendor_id'))
        ->where('vendor_status.display_order', '=', '1')
        ->get();
        $status = $statuslist[0]->id;
        $this->jobcard_email($jobcrd,$status,Session::get('logged_vendor_id'),'email_sent',Session::get('tax_enabled'));

        /***************Email Sending ends ****************************************/


        }


        if ($saved) {
            $savestatus++;
        }
        if ($savestatus > 0) {
            $status_data['status']  = 'success';
        } else {
            $status_data['status']  = 'fail';
        }


        return $status_data;
       // return response::json(['status' => $status, 'response_code' => $response_code,'email_status'=>$email_status]);
    }
    public function jobcard_email($jobcard,$status,$vendor_id,$mode,$taxenabled,$endstatus='N')
    {

        $products = $this->sendemail_sms($vendor_id,$status);
        if($products[0]->send_email == "Y")
        {
            $vendor=Vendor::select('*')->where('id','=',$vendor_id)->get();
            $servicelist =$this->servicelist_query_foredit_page($jobcard);
             $jobcard_cust = Jobcard::select('products.name as pdtname',  'job_card.remarks as remarks','customers.name as custname', 'customers.contact_number as custmobile','customers.email as email') //DB::table('job_card')
                ->leftjoin('customers', 'customers.id', '=', 'job_card.customer_id')
                ->join('products', 'products.id', '=', 'job_card.product_id')
                ->where('job_card.jobcard_number', '=', $jobcard)
                ->get();
                $status_list=Status::select('*')->where('id','=',$status)->get();
                $status_name=$status_list[0]->name;//Session::put('default_email'
            $data_email = array('cust_name'=>$jobcard_cust[0]->custname,
            'body' => "Thank u for ur Order",
            'cust_email' =>$jobcard_cust[0]->email,
            'jobcard' =>$jobcard,
            'vendor_details' =>$vendor,
            'cust_mobile' =>$jobcard_cust[0]->custmobile,
            'servicelist'=>$servicelist,
            'product_det'=>$jobcard_cust,
            'taxenabled'=>$taxenabled,
            'endstatus'=>$endstatus,
            'status'=>$status_name,
            'default_email'=>Session::get('default_email')
            );
            if($mode=='email_sent')
            {
                Mail::send('email.email', $data_email, function ($message) use ($data_email) {
                    $message->from($data_email['default_email'], 'Service Book');//$vendor[0]->mail_id; $vendor[0]->name;
                    $message->sender($data_email['default_email'], 'Service Book');//$vendor[0]->mail_id; $vendor[0]->name;
                    $message->to($data_email['cust_email'], $data_email['cust_name']);
                    //$message->cc('john@johndoe.com', 'John Doe');
                    //$message->bcc('john@johndoe.com', 'John Doe');
                   // $message->replyTo('webdev.potafo@gmail.com', 'Service Book');
                    $message->subject('Received Your Order');
                   // $message->priority(3);
                   // $message->attach('pathToFile');
                });

                if( count(Mail::failures()) > 0 ) {

                    $status_data['email_status']="Email Sending failed";

                 } else {
                    $status_data['email_status']="Email Sent successfully";
                 }
                 return $status_data['email_status'];
            }elseif($mode=='email_view')
            {
                return $data_email;
            }

        }

    }
    public function jobcard_view(Request $request)
    {
        if(strpos(Session::has('jobcard_reference'),"Temp-") === true){


            if (Session::has('jobcard_reference')) {
                $jobcard = Jobcard::firstOrFail()->where('jobcard_number','like', Session::get('jobcard_reference').'%');
                $saved = $jobcard->delete($jobcard);

                $jobcard = Cart::firstOrFail()->where('jobcard_reference','like',  Session::get('jobcard_reference').'%');
                $saved = $jobcard->delete($jobcard);

                $jobcard = StatusChange::firstOrFail()->where('jobcard_number','like', Session::get('jobcard_reference').'%');
                $saved = $jobcard->delete($jobcard);

                // $jobcard = Customer::firstOrFail()->where('id', $request['customerid']);
                // $saved = $jobcard->delete($jobcard);
            }
        }

        Session::forget('jobcard_reference');
        Session::forget('customerid');
        //DB::enableQueryLog();
        $status_list = DB::table('vendor_status')
            //->select('vendor_status.status_id')
            ->where('vendor_status.vendor_id', '=', Session::get('logged_vendor_id'))
            ->where('vendor_status.active', '=', 'Y')
            ->where('vendor_status.ending_status', '=', '1')
            ->pluck('vendor_status.status_id')->toArray();;
            //print_r($status_list);dd();
         //   dd(DB::getQueryLog());
        $rows1 = Jobcard::leftjoin('products', 'products.id', '=', 'job_card.product_id')
            ->leftjoin('customers', 'customers.id', '=', 'job_card.customer_id')
            ->select('customers.name as custname', 'customers.id as custid', 'customers.contact_number as custmobile', 'products.name as pdtname', 'job_card.jobcard_number', 'job_card.created_at', 'job_card.id')
            ->orderBy('job_card.created_at', 'DESC')
            ->where('job_card.jobcard_number','not like','Temp-%')
            //->where('job_card.current_status',\DB::raw($status_list),">",\DB::raw("'0'"))
            ->whereNotIn('job_card.current_status',$status_list);
        $jobcard = array();
        if (Session::get('logged_user_type') == '3') {
            $vendor_id = Session::get('logged_vendor_id');
            $jobcard = $rows1->where('job_card.vendor_id', '=', $vendor_id);
        } else if (Session::get('logged_user_type') == '1') {
        }
        $jobcard = $rows1->paginate(Session::get('paginate'));
        //dd(DB::getQueryLog());
        //     DB::enableQueryLog();
        return view('jobcard.jobcard', compact('jobcard'));
    }
    public function jobcard_add(Request $request)
    {
        $products = array();
        if (Session::get('logged_user_type') == '3') {
            $products = $this->product_list_query(Session::get('logged_vendor_id'));
        }
        $service = DB::table('vendor_servicetype')
            ->join('service_type', 'service_type.id', '=', 'vendor_servicetype.service_type')
            ->select('service_type.*')
            ->where('service_type.service_category', '=', '1')
            ->where('vendor_servicetype.vendor_id', '=', Session::get('logged_vendor_id'))
            ->get();
        if (!Session::has('jobcard_reference')) {
            $jobcard_reference ="Temp-".Session::get('logged_vendor_shortcode') . mt_rand(1000000, 99999999);
            Session::put('jobcard_reference', $jobcard_reference);
            Session::put('jobcard_count', "1");
        }
        $customer = array();
        if (Session::has('customerid')) {
            $customer = Customer::select('customers.*')
                ->where('customers.id', '=', Session::get('customerid'))
                ->get();
        }
        return view('jobcard.jobcard_add', compact('products',  'customer'));
    }
    //product_list vendor_name jobcardnumber
    public function validate_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_list' => 'required|string|max:50',
        ], [
            'product_list.required' => 'Product List is required',
        ]);
        $validator->sometimes('generalservice.*', 'required', function ($request) {
            return $request['productservice'] === "";
        });
        $validator->sometimes('productservice.*', 'required', function ($request) {
            return $request['generalservice'] === "";
        });
        return $validator;
    }
    public function validate_jobcard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jobcard_name' => 'required|string|max:50',
            'jobcard_mobile' => 'required|string|max:50',
        ], [
            'jobcard_name.required' => 'Name is required',
            'jobcard_mobile.required' => 'Mobile is required'
        ]);
        return $validator;
    }
    public function insert(Request $request)
    {
        $validator = $this->validate_jobcard($request);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return Redirect()->back()->with('errors', $errors)->withInput($request->all());
        } else {
            $resmsg=$this->insert_jobcard($request);
            //email_status status
            Session::forget('jobcard_reference');
            Session::forget('customerid');
            return Redirect('jobcard')->with('status', 'Jobcard Successfully Added!');
        }
    }

    public function jobcard_edit(Request $request, $id)
    {
        $jobcard_cust = array();
        // DB::enableQueryLog();
        $jobcard_cust = Jobcard::select('products.name as pdtname', 'products.id as pdtid', 'job_card.remarks', 'customers.name', 'job_card.jobcard_number', 'customers.contact_number as mobile', 'job_card.vendor_id', 'cart.jobcard_reference') //DB::table('job_card')
            ->join('cart', 'cart.jobcard_reference', '=', 'job_card.jobcard_number')
            ->join('customers', 'customers.id', '=', 'job_card.customer_id')
            ->join('products', 'products.id', '=', 'job_card.product_id')
            ->where('job_card.id', '=', $id)
            ->get();

        // dd(DB::getQueryLog());
        Session::put('jobcard_reference', $jobcard_cust[0]->jobcard_reference);
        $products = array();
        if (Session::get('logged_user_type') == '3') {
            $products = $this->product_list_query(Session::get('logged_vendor_id'));
        } else if (Session::get('logged_user_type') == '1') {
            $products = $this->product_list_query($jobcard_cust[0]->vendor_id);
        }
        $product_id = $jobcard_cust[0]->pdtid;

        $productservice_id = Session::get('Products');
        $Generalservice_id = Session::get('General');
        $product_service = DB::table('service')
            ->select('service.*')
            ->where('service.type', '=', $productservice_id)
            ->where('service.product_id', '=', $product_id)
            ->get();
        $general_service = DB::table('service')
            ->select('service.*')
            ->where('service.type', '=', $Generalservice_id)
            ->get();
        $servicelist = $this->service_full_listedit($jobcard_cust[0]->jobcard_reference);
        $serviceids = Cart::where('cart.jobcard_reference', '=', $jobcard_cust[0]->jobcard_reference)
            ->pluck('cart.service_id')->toArray();
        $vendor_current_status = StatusChange::select('vendor_status.display_order', 'status.name as stname', 'status.id as id')
            ->join('status', 'status_change.to_status', '=', 'status.id')
            ->join('vendor_status', 'vendor_status.status_id', '=', 'status.id')
            ->where('status_change.jobcard_number', '=', $jobcard_cust[0]->jobcard_reference)
            ->orderBy('status_change.created_at', 'DESC')
            ->get();
        $vendor_status = DB::table('vendor_status')
            ->select('status.name', 'status.id','vendor_status.ending_status')
            ->join('status', 'status.id', '=', 'vendor_status.status_id')
            ->where('vendor_status.vendor_id', '=', Session::get('logged_vendor_id'))
            ->where('vendor_status.display_order', '>', $vendor_current_status[0]->display_order)
            ->orderBy('vendor_status.display_order', 'ASC')
            ->get();
        $vendor_partslist = array();
        if (Session::get('Parts_status') == 'Y') {
            $vendor_partslist = DB::table('service')
                ->select('service.id', 'service.name', 'service_pricedetails.actual_price')
                ->join('service_pricedetails', 'service_pricedetails.service_id', '=', 'service.id')
                ->where('service.type', '=', Session::get('Parts'))
                ->get();
        }

        return view('jobcard.jobcard_edit', compact('jobcard_cust', 'id', 'vendor_partslist', 'products', 'serviceids', 'general_service', 'product_service', 'servicelist', 'vendor_status', 'vendor_current_status'));
    }

    public function update(Request $request)
    {
        $validator = $this->validate_data($request);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return Redirect()->back()->with('errors', $errors)->withInput($request->all());
        } else {
            $updated = $this->update_sql($request);
            return Redirect('jobcard')->with('status', 'Job card Updated successfully!');
        }
    }
    public function update_sql(Request $request)
    {
        $savestatus = 1;
        $data['product_id']           = $request['product_list'];
        $data['vendor_id']       = $request['vendor_name'];
        $jobcard = Jobcard::findOrFail($request['jobcard_id']);
        $saved = $jobcard->update($data);
        if ($saved) {
            $savestatus++;
        }
        return $savestatus;
    }

    public function service_insert(Request $request)
    {
        $validator = $this->validate_data($request);
        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()]);
        } else {
            $jobcard_reference = $request['jobcardnumber_ref'];
            $saved = $this->insert_jobcardservices($request);
            return Response::json(['success' => 1]);
        }
    }
    public function parts_insert(Request $request)
    {
        $saved = $this->insert_jobcardparts($request);
        return Response::json(['success' => 1]);
    }
    public function insert_jobcardparts(Request $request)
    {
        $savestatus = 0;
        $servicelist = ServicePriceDetails::select('service_pricedetails.*')
            ->where('service_pricedetails.service_id', '=', $request['parts_list'])
            ->get();
        $jobcardnumber = Session::get('logged_vendor_shortcode') . mt_rand(1000000, 99999999);
        $jobcard_servcs = new Cart();
        $jobcard_servcs->jobcard_reference      = $request['jobcardnumber_ref'];
        $jobcard_servcs->jobcard_number         = $jobcardnumber;
        $jobcard_servcs->service_id             = $request['parts_list'];
        $jobcard_servcs->service_name           = $request['parts_name'];
        $taxpercent  = intval($servicelist[0]->tax_sgst)  + intval($servicelist[0]->tax_cgst);
        $taxamount   = (intval($request['parts_price'])  * intval($taxpercent)) / 100;
        $jobcard_servcs->price                  = $request['parts_price'];
        $jobcard_servcs->actual_price           = $servicelist[0]->actual_price;
        $jobcard_servcs->tax_percent            = $taxpercent;
        $jobcard_servcs->tax_amount             = $taxamount;
        $jobcard_servcs->total_with_tax         = intval($taxamount)  + intval($request['parts_price']);
        $jobcard_servcs->total_without_tax      = $request['parts_price'];
        $saved = $jobcard_servcs->save();
        if ($saved) {
            $savestatus++;
        }
        if ($savestatus > 0) {
            return 1;
        } else {
            return 0;
        }
    }
    public function insert_jobcardservices(Request $request)
    {
        $savestatus = 0;
        foreach ($request['service'] as $list) {
            $servicelist = ServicePriceDetails::select('service_pricedetails.*', 'service.name')
                ->join('service', 'service.id', '=', 'service_pricedetails.service_id')
                ->where('service_pricedetails.service_id', '=', $list)
                ->get();
            $jobcardnumber = Session::get('logged_vendor_shortcode') . mt_rand(1000000, 99999999);

            $jobcard_servcs = new Cart();
            $jobcard_servcs->jobcard_reference      = $request['jobcardnumber_ref']."-".Session::get('jobcard_count');
            $jobcard_servcs->jobcard_number         = $jobcardnumber;
            $jobcard_servcs->service_id             = $list;
            $jobcard_servcs->service_name             = $servicelist[0]->name;
            $taxpercent  = intval($servicelist[0]->tax_sgst)  + intval($servicelist[0]->tax_cgst);
            $taxamount   = (intval($servicelist[0]->actual_price)  * $taxpercent) / 100;
            $jobcard_servcs->price                  = $servicelist[0]->actual_price;
            $jobcard_servcs->actual_price           = $servicelist[0]->actual_price;
            $jobcard_servcs->tax_percent            = $taxpercent;
            $jobcard_servcs->tax_amount             = $taxamount;
            $jobcard_servcs->total_with_tax         = $taxamount + intval($servicelist[0]->actual_price);
            $jobcard_servcs->total_without_tax      = $servicelist[0]->actual_price;
            //$jobcard_servcs->remarks      = $request['jobcard_remarks'];
            $saved = $jobcard_servcs->save();
        }
        if (Session::get('logged_user_type') == '3') {
            $vendorid = Session::get('logged_vendor_id');
        } else if (Session::get('logged_user_type') == '1') {
            $vendorid               = $request['vendor_name'];
        }
        if(!Session::has('customerid'))
        {
            $customer = new Customer();
            $customer->vendor_id                = $vendorid;
            $saved = $customer->save();
            $insertedId = $customer->id;
            if (!Session::has('customerid')) {
                Session::put('customerid', $insertedId);
            }
        }else
        {
            $insertedId = Session::get('customerid');
        }

        $jobcard = new Jobcard();
        $jobcard->user_id               = Session::get('logged_user_id');
        $jobcard->vendor_id             = $vendorid;
        $jobcard->jobcard_number        = Session::get('jobcard_reference')."-".Session::get('jobcard_count');
        $jobcard->customer_id           = $insertedId;
        $jobcard->product_id            = $request['product_list'];
        $jobcard->remarks               = $request['jobcard_remarks'];
        $jobcard->service_remarks               = $request['service_remarks'];
        $jobcard->date               = date("Y-m-d");
        $saved = $jobcard->save();

        $statuslist = DB::table('vendor_status')
            ->select('vendor_status.status_id as id')
            ->where('vendor_status.vendor_id', '=', Session::get('logged_vendor_id'))
            ->where('vendor_status.display_order', '=', '1')
            ->get();
        $status = $statuslist[0]->id;
        $statuschange = new StatusChange();
        $statuschange->jobcard_number               = Session::get('jobcard_reference')."-".Session::get('jobcard_count');
        $statuschange->from_status               = 0;
        $statuschange->to_status               = $status;
        $statuschange->change_by               = Session::get('logged_vendor_id');
        $statuschange->date                     = date('Y-m-d');
        $statuschange->save();

        $statuschangehistory = new StatusChangeHistory();
        $statuschangehistory->jobcard_number               = Session::get('jobcard_reference')."-".Session::get('jobcard_count');
        $statuschangehistory->from_status               = 0;
        $statuschangehistory->to_status               = $status;
        $statuschangehistory->change_by               = Session::get('logged_vendor_id');
        $statuschangehistory->date                     = date('Y-m-d');
        $statuschangehistory->save();

        $pdtcount=intval(Session::get('jobcard_count'));
        Session::put('jobcard_count', ($pdtcount + 1));
        if ($saved) {
            $savestatus++;
        }
        if ($savestatus > 0) {
            return 1;
        } else {
            return 0;
        }
    }
    public function load_jobcardservice_list(Request $request)
    {//DB::enableQueryLog();
        $servicelist = Cart::select('cart.*',  'products.name as pdtname', 'service.id as sid', 'cart.id as cid', 'job_card.id as jid', 'job_card.customer_id as customer_id','job_card.remarks','job_card.service_remarks',\DB::raw("GROUP_CONCAT(service.name) as sname"))
            ->leftjoin("service", "service.id", '=', 'service_id')
            ->join('job_card', 'job_card.jobcard_number', '=', 'cart.jobcard_reference')
            ->join('products', 'products.id', '=', 'job_card.product_id')
            ->where('cart.jobcard_reference', 'LIKE', $request['ref']."%")
            ->paginate(Session::get('paginate'));
           // ->get();
           // dd(DB::getQueryLog());
        $append = '';
        $links = '';
        if (count($servicelist) > 0) {
            $i = 1;
            foreach ($servicelist as $value) {
                if (!Session::has('customerid')) {
                    Session::put('customerid', $value->customer_id);
                }
                //$value->jobcard_number
                $append .= "
                <tr>
                <td>
                    $i
                </td>

                <td>
                     $value->pdtname
                </td>
                <td>
                $value->remarks
                </td>
                <td>
                   $value->sname
                </td>
                <td>
                $value->service_remarks
                </td>
                <td>";
               // if (count($servicelist)>1) {
                    $append .="   <a style='color: #ba54f5; cursor: pointer;' data-toggle='modal' id='deleteButton' data-target='#delete_services' data-jobcardid='" . $value->jid . "'  data-customerid='" . $value->customer_id . "' data-jobcardref='" . $value->jobcard_reference . "' data-jobcardnmbr='" . $value->jobcard_number . "' data-id='" . $value->cid . "' title='Delete Service'>
                    <i class='tim-icons icon-trash-simple'></i>
                </a>";
                //}else{
                    //$append .="<i style='color:red' class='tim-icons icon-simple-remove'></i>";
                //}
                $append .="</td>

                </tr>";
                // <a style='color: #ba54f5; cursor: pointer;'  data-toggle='modal' data-target='#productsInsert' data-type='update' data-service='".$value->sid."'  data-pdtid='".$value->id."' data-jobcardref ='".Session::get('jobcard_reference')."' data-jobcardnmbr='".$value->jobcard_number."' data-id='".$value->id."' >
                // <i class='tim-icons icon-pencil'></i>
                //           </a>

                $i++;
            }
            $links =""; // $servicelist->links()->render();
        }
        return Response::json(['append' => $append, 'links' => $links]);//, 'links' => $links
        //return $append;


    }
    public function getServiceList(Request $request)
    {
        // dd($request['gservice']);
        $product_id = $request['product_id'];
        $productservice_id = Session::get('Products');
        $Generalservice_id = Session::get('General');
        $product_service = DB::table('service')
            ->select('service.*')
            ->where('service.type', '=', $productservice_id)
            ->where('service.product_id', '=', $product_id)
            ->get();
            if (Session::get('logged_user_type') == '3') {
                $general_service = DB::table('service')
                ->select('service.*')
                ->where('service.type', '=', $Generalservice_id)
                ->where('service.vendor_id', '=', Session::get('logged_vendor_id'))
                ->get();
            }else
            {
                $general_service = DB::table('service')
                ->select('service.*')
                ->where('service.type', '=', $Generalservice_id)
                ->get();
            }

        $productservice = array();
        $generalservice = array();
        $append = '';
        $append .= '<div class="form-group" >';
        $append .= '<label>General Service</label><br>';
        $append .= '<table >';
        if (isset($request['gservice'])) {
            $generalservice = explode(',', $request['gservice']);
        }
        $i = 0;
        foreach ($general_service as $list) {
            $checked = '';
            if (in_array($list->id, $generalservice))
                $checked = " checked ";
            $i++;
            if ($i == 1) {
                $append .= '<tr >';
            }
            $append .= '            <td width="40%">';
            $append .= '                <div class="form-check form-check-inline">';
            $append .= '                    <label class="form-check-label">';
            $append .= '                        <input class="form-check-input" type="checkbox" name="service[]" ' . $checked . '  id="inlineCheckbox_gs' . $list->id . '" value="' . $list->id . '">' . $list->name;
            $append .= '                        <span class="form-check-sign"></span>';
            $append .= '                    </label>';
            $append .= '                </div>';
            $append .= '            </td>';
            if ($i % 3 == 0) {
                $append .= '                </tr> <tr>';
            }
        }
        $append .= '    </table>';
        $append .= '</div>';
        $append .= '<br>';
        $append .= '<div class="form-group" >';
        $append .= '<label>Product Service</label><br>';
        $append .= '<table >';
        $i = 0;
        if (isset($request['pservice'])) {
            $productservice = explode(',', $request['pservice']);
        }

        foreach ($product_service as $list) {
            $checked = '';
            if (in_array($list->id, $productservice))
                $checked = " checked ";

            $i++;
            if ($i == 1) {
                $append .= '<tr >';
            }
            $append .= '            <td width="40%">';
            $append .= '                <div class="form-check form-check-inline">';
            $append .= '                    <label class="form-check-label">';
            $append .= '                        <input class="form-check-input" type="checkbox" name="service[]" ' . $checked . ' id="inlineCheckbox_gs' . $list->id . '" value="' . $list->id . '">' . $list->name;
            $append .= '                        <span class="form-check-sign"></span>';
            $append .= '                    </label>';
            $append .= '                </div>';
            $append .= '            </td>';
            if ($i % 3 == 0) {
                $append .= '                </tr> <tr>';
            }
        }
        $append .= '    </table>';

        $append .= '</div>';
        $append .= '<br>';

        $append .='<div class="form-group ">';
        $append .=                '<label for="exampleFormControlInput1">Service Remarks</label>';
        $append .=               '<input type="text" class="form-control" id="service_remarks" style=" color: black;" name="service_remarks" placeholder="Service Remarks" >';
        $append .=            '</div>';


        return $append;
    }
    public function getServiceList_old(Request $request)
    {
        // dd($request['gservice']);
        $product_id = $request['product_id'];
        $general_service = DB::table('service')
            ->select('service.*')
            ->where('service.type', '=', '2')
            ->get();
        $product_service = DB::table('service')
            ->select('service.*')
            ->where('service.type', '=', '1')
            ->where('service.product_id', '=', $product_id)
            ->get();
        $productservice = array();
        $generalservice = array();
        $append = '';
        $append .= '<div class="form-group" >';
        $append .= '<label>General Service</label><br>';
        $append .= '<table >';
        if (isset($request['gservice'])) {
            $generalservice = explode(',', $request['gservice']);
        }
        $i = 0;
        foreach ($general_service as $list) {
            $checked = '';
            if (in_array($list->id, $generalservice))
                $checked = " checked ";
            $i++;
            if ($i == 1) {
                $append .= '<tr >';
            }
            $append .= '            <td width="40%">';
            $append .= '                <div class="form-check form-check-inline">';
            $append .= '                    <label class="form-check-label">';
            $append .= '                        <input class="form-check-input" type="checkbox" name="generalservice[]" ' . $checked . '  id="inlineCheckbox_gs' . $list->id . '" value="' . $list->id . '">' . $list->name;
            $append .= '                        <span class="form-check-sign"></span>';
            $append .= '                    </label>';
            $append .= '                </div>';
            $append .= '            </td>';
            if ($i % 3 == 0) {
                $append .= '                </tr> <tr>';
            }
        }
        $append .= '    </table>';
        $append .= '</div>';
        $append .= '<br>';
        $append .= '<div class="form-group" >';
        $append .= '<label>Product Service</label><br>';
        $append .= '<table >';
        $i = 0;
        if (isset($request['pservice'])) {
            $productservice = explode(',', $request['pservice']);
        }

        foreach ($product_service as $list) {
            $checked = '';
            if (in_array($list->id, $productservice))
                $checked = " checked ";

            $i++;
            if ($i == 1) {
                $append .= '<tr >';
            }
            $append .= '            <td width="40%">';
            $append .= '                <div class="form-check form-check-inline">';
            $append .= '                    <label class="form-check-label">';
            $append .= '                        <input class="form-check-input" type="checkbox" name="productservice[]" ' . $checked . ' id="inlineCheckbox_gs' . $list->id . '" value="' . $list->id . '">' . $list->name;
            $append .= '                        <span class="form-check-sign"></span>';
            $append .= '                    </label>';
            $append .= '                </div>';
            $append .= '            </td>';
            if ($i % 3 == 0) {
                $append .= '                </tr> <tr>';
            }
        }
        $append .= '    </table>';

        $append .= '</div>';
        $append .= '<br>';


        return $append;
    }
    public function delete($id)
    {
        $project = Cart::find($id);
        return view('jobcard.jobcard_delete', compact('project'));
    }
    public function fielddelete(Request $request)
    {
        $cartid = "";
        if (isset($request['fromeditpage']) == 1) {
            $cartid = $request['referenceid'];
        } else {
            $cartid = $request['cartid'];
        }
        $cart = Cart::findOrFail($cartid);
        $saved = $cart->delete($cart);

        $jobcardref = "";
        if (isset($request['fromeditpage']) == 1) {
            $jobcardref = $request['referencenumber'];
        } else {
            $jobcardref = $request['jobcardreference'];
        }
        if (isset($request['fromeditpage']) > 1) {
            $cartcheck = Cart::select('cart.*')
                ->where('cart.jobcard_reference', '=', $jobcardref)
                ->get();
            $cartcount = count($cartcheck);
            if ($cartcount == 0) {
                $cust = Customer::findOrFail($request['customerid']);
                $saved = $cust->delete($cust);
            }
        }
        if (isset($request['fromeditpage']) == 1) {
            return Redirect()->back()->with('status', 'JobCard Deleted successfully!');
        } else {
            return Redirect('jobcard_add')->with('status', 'JobCard Deleted successfully!');
        }
    }
    public function service_update(Request $request)
    {

        $saved = $this->update_jobcardservices($request);
        return Response::json(['success' => 1]);
    }
    public function update_jobcardservices(Request $request)
    {

        $savestatus = 1;
        foreach ($request['service'] as $list) {
            $cartcheck = Cart::where('jobcard_reference', '=', $request['jobcardnumber_ref'])
                ->where('service_id', '=', $list)->get();
            if (count($cartcheck) == 0) {
                $servicelist = ServicePriceDetails::select('service_pricedetails.*', 'service.name')
                    ->join('service', 'service.id', '=', 'service_pricedetails.service_id')
                    ->where('service_pricedetails.service_id', '=', $list)
                    ->get();
                $jobcardnumber = Session::get('logged_vendor_shortcode') . mt_rand(1000000, 99999999);

                $jobcard_servcs = new Cart();
                $jobcard_servcs->jobcard_reference      = $request['jobcardnumber_ref'];
                $jobcard_servcs->jobcard_number         = $jobcardnumber;
                $jobcard_servcs->service_id             = $list;
                $jobcard_servcs->service_name            = $servicelist[0]->name;
                $taxpercent  = intval($servicelist[0]->tax_sgst)  + intval($servicelist[0]->tax_cgst);
                $taxamount   = (intval($servicelist[0]->actual_price)  * $taxpercent) / 100;
                $jobcard_servcs->price                  = $servicelist[0]->actual_price;
                $jobcard_servcs->actual_price           = $servicelist[0]->actual_price;
                $jobcard_servcs->tax_percent            = $taxpercent;
                $jobcard_servcs->tax_amount             = $taxamount;
                $jobcard_servcs->total_with_tax         = $taxamount + intval($servicelist[0]->actual_price);
                $jobcard_servcs->total_without_tax      = $servicelist[0]->actual_price;
                $saved = $jobcard_servcs->save();
            }
        }
    }

    public function fielddelete_each(Request $request)
    {
        $jobcard = Jobcard::findOrFail($request['referenceid']);
        $saved = $jobcard->delete($jobcard);

        $jobcard = Cart::firstOrFail()->where('jobcard_reference', $request['referencenumber']);
        $saved = $jobcard->delete($jobcard);

        $jobcard = StatusChange::firstOrFail()->where('jobcard_number', $request['referencenumber']);
        $saved = $jobcard->delete($jobcard);

        $jobcard = Customer::firstOrFail()->where('id', $request['customerid']);
        $saved = $jobcard->delete($jobcard);

        return Redirect('jobcard')->with('status', 'JobCard Deleted successfully!');
    }
    public function service_full_listedit($jobcardnumber)
    {
        $servicelist = Cart::select('cart.*', 'service.name as sname')
            ->leftjoin("service", 'service.id', '=', 'cart.service_id')
            ->join('job_card', 'job_card.jobcard_number', '=', 'cart.jobcard_reference')
            ->where('cart.jobcard_reference', '=', $jobcardnumber) //$request['ref'])
            ->orderBy('created_at', 'DESC')
            ->paginate(Session::get('paginate'));
        return $servicelist;
    }
    public function service_price_list($servceid)
    {
        $pricelist = ServicePriceDetails::select('*')
            ->where('service_id', '=', $servceid)
            ->get();
        return $pricelist;
    }
    public function servicelist_query_foredit_page($jobcard)
    {
        $servicelist = Cart::select('cart.*','job_card.remarks','job_card.service_remarks')
        ->leftjoin("service", 'service.id', '=', 'cart.service_id')
        ->join('job_card', 'job_card.jobcard_number', '=', 'cart.jobcard_reference')
        ->where('cart.jobcard_reference', '=', $jobcard)
        ->orderBy('created_at', 'DESC')
        ->get();
        return $servicelist;
    }
    public function load_jobcardservice_list_edit(Request $request)
    {

        // $servicelist = Cart::select('cart.*','job_card.remarks','job_card.service_remarks')
        //     ->leftjoin("service", 'service.id', '=', 'cart.service_id')
        //     ->join('job_card', 'job_card.jobcard_number', '=', 'cart.jobcard_reference')
        //     ->where('cart.jobcard_reference', '=', $request['ref'])
        //     ->orderBy('created_at', 'DESC')
        //     ->get();
            //->paginate(Session::get('paginate'));
            $servicelist =$this->servicelist_query_foredit_page($request['ref']);
        $append = '';

        if (count($servicelist) > 0) {
            $i = 1;
            $final = 0;
            $tax_total=0;
            foreach ($servicelist as $value) {
                $price_each = $value->price;
                $taxval = 0;
                $total = 0;
                if (Session::get('tax_enabled') == 'Y') {
                    $taxval = $value->tax_amount;
                    $tax_total=intval($tax_total) + intval($taxval);
                }

                $total = intval($taxval)  + intval($price_each);
                $final = intval($final) + intval($total);

                $append .= "
                <tr>
                <td>
                    $i
                </td>

                <td>
                <div contentEditable='true' class='edit' id='servicename_" . $value->id . "'> " . $value->service_name . "</div>

                </td>
                <td>
                $value->service_remarks
            </td>
                <td>
                <div contentEditable='true' class='edit' id='serviceprice_" . $value->id . "'> " . $price_each . "</div>

             </td>
            ";
                if (Session::get('tax_enabled') == 'Y') {
                    $append .= "<td>
                        $taxval
                    </td>
                    ";
                }
                $append .= "

                <td>
          $total
            </td>
            <td>";

            if (count($servicelist)>1){
                $append .= "
                <a style='color: #ba54f5; cursor: pointer;' data-toggle='modal' id='deleteButton' data-target='#delete_services' data-jobcardrefnmbr='" . $value->jobcard_reference . "' data-jobcardnmbr='" . $value->jobcard_number . "' data-id='" . $value->id . "' title='Delete Service'>
                <i class='tim-icons icon-trash-simple'></i>
                </a>
                ";
            }else{
                $append .="<i style='color:red' class='tim-icons icon-simple-remove'></i>";
            }
            $append .= "     </td>
                </tr>";
                // <a style='color: #ba54f5; cursor: pointer;' class='loadeditpage' data-toggle='modal' data-target='#productsInsert' data-type='update' data-pdtservice='".$value->productservice."' data-genservice='".$value->generalservice."' data-pdtid='".$value->pid."' data-jobcardref ='".Session::get('jobcard_reference')."' data-jobcardnmbr='".$value->jobcard_number."' data-id='".$value->id."' >
                // <i class='tim-icons icon-pencil'></i>
                //           </a>
                $i++;
            }
            if (Session::get('tax_enabled') == 'Y') {
                $append .= "<tr>
            <td> </td><td> </td><td> </td>
            <td>Total Tax =</td><td>" . $tax_total   . "</td><td> </td></tr>";
            }
            $append .= "<tr>
            <td> </td><td> </td><td> </td>
            <td>Total =</td><td>" . $final   . "</td><td> </td></tr>";
            $links ="";// $servicelist->links()->render();
        }
        return Response::json(['append' => ($append), 'links' => $links,'final'=>$final,'tax_total'=>$tax_total]);
    }
    public function cart_edit(Request $request)
    {
        if ($request['field'] == "servicename")
            $data['service_name']           = $request['value'];
        if ($request['field'] == "serviceprice")
            $data['price']       = $request['value'];
        $jobcard = Cart::findOrFail($request['id']);
        $saved = $jobcard->update($data);
        return redirect()->back()->with('status', 'Updated successfully!');
    }
    public function jobcard_view_each(Request $request, $id)
    {
        $jobcard_cust = array();
        $jobcard_cust = Jobcard::select('job_card.name', 'jobcard_services.jobcard_number', 'job_card.mobile', 'jobcard_services.jobcard_reference', 'job_card.vendor_id') //DB::table('job_card')
            ->join('jobcard_services', 'jobcard_services.jobcard_reference', '=', 'job_card.jobcard_number')
            ->where('jobcard_services.id', '=', $id)
            ->get();
        Session::put('jobcard_reference', $jobcard_cust[0]->jobcard_reference);
        $products = array();
        if (Session::get('logged_user_type') == '3') {
            $products = $this->product_list_query(Session::get('logged_vendor_id'));
        } else if (Session::get('logged_user_type') == '1') {
            $products = $this->product_list_query($jobcard_cust[0]->vendor_id);
        }
        $general_service = DB::table('service')
            ->select('service.*')
            ->where('service.type', '=', '1')
            ->get();
        $product_service = DB::table('service')
            ->select('service.*')
            ->where('service.type', '=', '2')
            ->get();
        $vendor_current_status = StatusChange::select('status.display_order', 'status.name as stname', 'status.id as id')
            ->join('status', 'status_change.to_status', '=', 'status.id')
            ->where('status_change.jobcard_number', '=', $jobcard_cust[0]->jobcard_number)
            ->orderBy('status_change.created_at', 'DESC')
            ->get();
        $vendor_status = DB::table('status')
            ->select('status.*')
            ->where('status.vendor_id', '=', Session::get('logged_vendor_id'))
            ->where('status.display_order', '>', $vendor_current_status[0]->display_order)
            ->orderBy('status.display_order', 'ASC')
            ->get();
        $vendor_status_last = DB::table('status')
            ->select('status.*')
            ->where('status.vendor_id', '=', Session::get('logged_vendor_id'))
            ->orderBy('status.display_order', 'DESC')
            ->get(1);
        return view('jobcard.jobcard_view', compact('jobcard_cust', 'id', 'products', 'general_service', 'product_service', 'vendor_status', 'vendor_current_status', 'vendor_status_last'));
    }
    public function load_jobcardservice_list_view(Request $request)
    {
        $vendor_status_last = DB::table('status')
            ->select('status.*')
            ->where('status.vendor_id', '=', Session::get('logged_vendor_id'))
            ->orderBy('status.display_order', 'DESC')
            ->get(1);
        $servicelist = Cart::select('jobcard_services.*', 'products.id as pid', 'products.name as pdtname', \DB::raw("GROUP_CONCAT(service.name) as sname"))
            ->leftjoin("service", \DB::raw("FIND_IN_SET(service.id,jobcard_services.generalservice) OR  FIND_IN_SET(service.id,jobcard_services.productservice)"), ">", \DB::raw("'0'"))
            ->join('products', 'products.id', '=', 'jobcard_services.product_id')
            ->where('jobcard_services.jobcard_number', '=', $request['ref'])
            ->groupBy('jobcard_number')
            ->orderBy('created_at', 'DESC')
            ->paginate(Session::get('paginate'));
        $append = '';
        if (count($servicelist) > 0) {
            $i = 1;
            foreach ($servicelist as $value) {

                $append .= "
                <tr>
                <td>
                    $i
                </td>
                <td>
                     $value->jobcard_number
                </td>
                <td>
                     $value->pdtname
                </td>
                <td>
                   $value->sname
                </td>";
                if ($value->current_status != $vendor_status_last[0]->id) {
                    $append .= "<td>
                        <a style='color: #ba54f5; cursor: pointer;' class='loadeditpage' data-toggle='modal' data-target='#productsInsert' data-type='update' data-pdtservice='" . $value->productservice . "' data-genservice='" . $value->generalservice . "' data-pdtid='" . $value->pid . "' data-jobcardref ='" . Session::get('jobcard_reference') . "' data-jobcardnmbr='" . $value->jobcard_number . "' data-id='" . $value->id . "' >
                        <i class='tim-icons icon-pencil'></i>
                                  </a>
                                  </td>";
                }
                $append .= "</tr>";
                $i++;
            }
            $links = $servicelist->links()->render();
        }
        return Response::json(['append' => ($append), 'links' => $links]);
    }
    public function load_jobcard_number(Request $request)
    {
        $search = $request['search'];
        $request_field = $request['request'];
        if ($search == '') {
            $customers = Customer::orderby('name', 'asc')->select('id', 'name', 'contact_number', 'email')
                    ->groupBy('name')
                    ->groupBy('contact_number')
                    ->groupBy('email')->get(); //->limit(5)
        } else {
            $customers = '';
            if ($request_field == "jobcard_name") {
                $customers = Customer::orderby('name', 'asc')
                    ->select('id', 'name', 'contact_number', 'email')
                    ->where('name', 'like', '%' . $search . '%')
                    ->groupBy('name')
                    ->groupBy('contact_number')
                    ->groupBy('email')->get(); //->limit(5)
            } else if ($request_field == "jobcard_mobile") {
                $customers = Customer::orderby('name', 'asc')
                    ->select('id', 'name', 'contact_number', 'email')
                    ->where('contact_number', 'like', '%' . $search . '%')
                    ->groupBy('name')
                    ->groupBy('contact_number')
                    ->groupBy('email')->get(); //->limit(5)
            } else if ($request_field == "jobcard_email") {
                $customers = Customer::orderby('name', 'asc')
                    ->select('id', 'name', 'contact_number', 'email')
                    ->where('email', 'like', '%' . $search . '%')
                    ->groupBy('name')
                    ->groupBy('contact_number')
                    ->groupBy('email')->get(); //->limit(5)
            }
        }
        $response = array();
        foreach ($customers as $customer) {
            if ($request_field == "jobcard_name") {
                $response[] = array("value" => $customer->id, "label" => $customer->name, "id" => $customer->id, "name" => $customer->name, "mobile" => $customer->contact_number, "email" => $customer->email);
            } else if ($request_field == "jobcard_mobile") {
                $response[] = array("value" => $customer->id, "label" => $customer->contact_number, "id" => $customer->id, "name" => $customer->name, "mobile" => $customer->contact_number, "email" => $customer->email);
            } else if ($request_field == "jobcard_email") {
                $response[] = array("value" => $customer->id, "label" => $customer->email, "id" => $customer->id, "name" => $customer->name, "mobile" => $customer->contact_number, "email" => $customer->email);
            }
        }

        return response()->json($response);
    }
    public function updatestatus(Request $request)
    {
        $data['current_status']       = $request['vendor_status'];
        $jobcard = Jobcard::where('jobcard_number', $request['jobcardnumber_up'])->firstOrFail();
        $saved = $jobcard->update($data);
        $vendor_current_status = array();
        $vendor_current_status = StatusChange::select('status_change.to_status')
            ->where('status_change.jobcard_number', '=', $request['jobcardnumber_up'])
            ->orderBy('status_change.created_at', 'DESC')
            ->get();
        $statuschange = new StatusChange();
        $statuschange->jobcard_number               = $request['jobcardnumber_up'];
        $statuschange->from_status               = $vendor_current_status[0]->to_status;
        $statuschange->to_status               = $request['vendor_status'];
        $statuschange->change_by               = Session::get('logged_vendor_id');
        $statuschange->date                     = date('Y-m-d');
        $statuschange->save();

        $statuschangehistory = new StatusChangeHistory();
        $statuschangehistory->jobcard_number               = $request['jobcardnumber_up'];
        $statuschangehistory->from_status               = $vendor_current_status[0]->to_status;
        $statuschangehistory->to_status               = $request['vendor_status'];
        $statuschangehistory->change_by               = Session::get('logged_vendor_id');
        $statuschangehistory->date                     = date('Y-m-d');
        $statuschangehistory->save();
//jobcardendingstatus
        if($request['jobcardendingstatus']=="1")
        {
            $this->jobcard_email($request['jobcardnumber_up'],$request['vendor_status'],Session::get('logged_vendor_id'),'email_sent',Session::get('tax_enabled'),'Y');
        }else
        {
            $this->jobcard_email($request['jobcardnumber_up'],$request['vendor_status'],Session::get('logged_vendor_id'),'email_sent',Session::get('tax_enabled'));
        }
        if($request['jobcardendingstatus']=="1")
        {//'jobcard_number', 'bill_amount', 'received_amount', 'discount_amount',
            $statuschange = new JobcardBills();
            $statuschange->jobcard_number                = $request['jobcardnumber_up'];
            $statuschange->bill_amount                   = $request['bill_amount'];
            $statuschange->received_amount               = $request['received_amount'];
            $statuschange->discount_amount               = $request['discount_amount'];
            $statuschange->tax_amount               = $request['tax_amount'];
            $statuschange->vendor_status=$request['vendor_status'];
            $statuschange->save();

            // if ending status then delete from statuschange then changes only in history table
            $jobcard = StatusChange::firstOrFail()->where('jobcard_number','=',$request['jobcardnumber_up']);
            $saved = $jobcard->delete($jobcard);

           return Redirect('jobcard')->with('status', 'Jobcard Successfully closed!');


        }

        return Redirect()->back()->with('status', 'Status Updated successfully!');
    }

    public function jobcard_history_view(Request $request)
    {
        if(strpos(Session::has('jobcard_reference'),"Temp-") === true){


            if (Session::has('jobcard_reference')) {
                $jobcard = Jobcard::firstOrFail()->where('jobcard_number','like', Session::get('jobcard_reference').'%');
                $saved = $jobcard->delete($jobcard);

                $jobcard = Cart::firstOrFail()->where('jobcard_reference','like',  Session::get('jobcard_reference').'%');
                $saved = $jobcard->delete($jobcard);

                $jobcard = StatusChange::firstOrFail()->where('jobcard_number','like', Session::get('jobcard_reference').'%');
                $saved = $jobcard->delete($jobcard);

                // $jobcard = Customer::firstOrFail()->where('id', $request['customerid']);
                // $saved = $jobcard->delete($jobcard);
            }
        }
        $products = array();
        if (Session::get('logged_user_type') == '3') {
            $products = $this->product_list_query(Session::get('logged_vendor_id'));
        } else if (Session::get('logged_user_type') == '1') {
            $products =array();// $this->product_list_query($jobcard_cust[0]->vendor_id);
        }
        $jobcard_status = DB::table('vendor_status')
        ->select('vendor_status.status_id as id','status.name')
        ->join('status','status.id','=','vendor_status.status_id')
        ->where('vendor_status.vendor_id', '=', Session::get('logged_vendor_id'))
        ->where('vendor_status.active', '=', 'Y')
        ->where('vendor_status.ending_status', '=', '1')
        ->get();
        Session::forget('jobcard_reference');
        Session::forget('customerid');
        //DB::enableQueryLog();
        $status_list = DB::table('vendor_status')
            //->select('vendor_status.status_id')
            ->where('vendor_status.vendor_id', '=', Session::get('logged_vendor_id'))
            ->where('vendor_status.active', '=', 'Y')
            ->where('vendor_status.ending_status', '=', '0')
            ->pluck('vendor_status.status_id')->toArray();
            //print_r($status_list);dd();
         //   dd(DB::getQueryLog());
        // $rows1 = Jobcard::leftjoin('products', 'products.id', '=', 'job_card.product_id')
        //     ->leftjoin('customers', 'customers.id', '=', 'job_card.customer_id')
        //     ->leftjoin('status', 'status.id', '=', 'job_card.current_status')
        //     ->leftjoin('jobcard_bills', 'jobcard_bills.jobcard_number', '=', 'job_card.jobcard_number')
        //     ->select('customers.name as custname', 'customers.id as custid', 'customers.contact_number as custmobile', 'products.name as pdtname', 'job_card.jobcard_number', 'job_card.date as jobcard_date', 'job_card.id','status.name as statusname','jobcard_bills.received_amount')
        //     ->orderBy('job_card.created_at', 'DESC')
        //     ->where('job_card.jobcard_number','not like','Temp-%')
        //     //->where('job_card.current_status',\DB::raw($status_list),">",\DB::raw("'0'"))
        //     ->whereNotIn('job_card.current_status',$status_list);
        // $jobcard = array();
        // if (Session::get('logged_user_type') == '3') {
        //     $vendor_id = Session::get('logged_vendor_id');
        //     $jobcard = $rows1->where('job_card.vendor_id', '=', $vendor_id);
        // } else if (Session::get('logged_user_type') == '1') {
        // }
        // $jobcard = $rows1->paginate(Session::get('paginate'));
        //dd(DB::getQueryLog());
        $filter_details['filter_fromdate']="";
        $filter_details['filter_todate']="";
        $filter_details['filter_status']="";
        $filter_details['filter_products']="";
        $filter_details['filter_globalsearch']='';
        $jobcard = array();
        $jobcard=$this->load_filter_results($request,'history');

        //     DB::enableQueryLog(); filter_todate filter_status filter_products

        return view('jobcard.jobcard_history', compact('jobcard','jobcard_status','products','filter_details'));
    }
    public function load_filter_results(Request $request,$type)
    {
        $status_list = DB::table('vendor_status')
        ->where('vendor_status.vendor_id', '=', Session::get('logged_vendor_id'))
        ->where('vendor_status.active', '=', 'Y')
        ->where('vendor_status.ending_status', '=', '0')
        ->pluck('vendor_status.status_id')->toArray();
         // DB::enableQueryLog();
        if($type=="history")
        {
            $rows1 = Jobcard::leftjoin('products', 'products.id', '=', 'job_card.product_id')
            ->leftjoin('customers', 'customers.id', '=', 'job_card.customer_id')
            ->leftjoin('status', 'status.id', '=', 'job_card.current_status')
            ->leftjoin('jobcard_bills', 'jobcard_bills.jobcard_number', '=', 'job_card.jobcard_number')
            ->select('customers.name as custname', 'customers.id as custid', 'customers.contact_number as custmobile', 'products.name as pdtname', 'job_card.jobcard_number', 'job_card.date as jobcard_date', 'job_card.id','status.name as statusname','jobcard_bills.received_amount')
            ->orderBy('job_card.created_at', 'DESC')
            ->where('job_card.jobcard_number','not like','Temp-%')
            //->where('job_card.current_status',\DB::raw($status_list),">",\DB::raw("'0'"))
            ->whereNotIn('job_card.current_status',$status_list);
        }else  if($type=="report")
        {
            $rows1 = Jobcard::leftjoin('customers', 'customers.id', '=', 'job_card.customer_id')
            ->leftjoin('jobcard_bills', 'jobcard_bills.jobcard_number', '=', 'job_card.jobcard_number')
            ->select('customers.name as custname', 'customers.id as custid', 'customers.contact_number as custmobile',  'job_card.jobcard_number', 'job_card.date as jobcard_date', 'job_card.id','jobcard_bills.received_amount','jobcard_bills.bill_amount','jobcard_bills.discount_amount','jobcard_bills.tax_amount')
            ->orderBy('job_card.created_at', 'DESC')
            ->where('job_card.jobcard_number','not like','Temp-%')
            //->where('job_card.current_status',\DB::raw($status_list),">",\DB::raw("'0'"))
            ->whereNotIn('job_card.current_status',$status_list);
        }

        $jobcard = array();
        if (Session::get('logged_user_type') == '3') {
            $vendor_id = Session::get('logged_vendor_id');
            $jobcard = $rows1->where('job_card.vendor_id', '=', $vendor_id);
        } else if (Session::get('logged_user_type') == '1') {
        }
        $filter_details=array();
        $filter_details['filter_fromdate']="";
        $filter_details['filter_todate']="";
        if (!empty($request->input('filter_fromdate')) && !empty($request->input('filter_todate'))) {
            $dfrom=date("Y-m-d",strtotime($request['filter_fromdate']));
            $dto=date("Y-m-d",strtotime($request['filter_todate']));
            $rows1->whereBetween('job_card.date', [$dfrom, $dto]);
            $filter_details['filter_fromdate']=$request['filter_fromdate'];
            $filter_details['filter_todate']=$request['filter_todate'];
        }
        if (!empty($request->input('filter_fromdate')) && empty($request->input('filter_todate'))) {
            $dfrom=date("Y-m-d",strtotime($request['filter_fromdate']));
            $dto=date("Y-m-d");
            $rows1->whereBetween('job_card.date', [$dfrom, $dto]);
            $filter_details['filter_fromdate']=$request['filter_fromdate'];
            $filter_details['filter_todate']="";
        }
        if (empty($request->input('filter_fromdate')) && !empty($request->input('filter_todate'))) {
            $dfrom=date("Y-m-d",strtotime($request['filter_todate']));
            $dto=date("Y-m-d",strtotime($request['filter_todate']));
            $rows1->whereBetween('job_card.date', [$dfrom, $dto]);
            $filter_details['filter_fromdate']="";
            $filter_details['filter_todate']=$request['filter_todate'];
        }
        $filter_details['filter_status']='';
        if($type=="history")
        {
            if (!empty($request->input('filter_status'))) {
                $jobcard = $rows1->where('job_card.current_status', $request->input('filter_status'));
                $filter_details['filter_status']=$request['filter_status'];

            }
        }

        $filter_details['filter_products']='';
        if($type=="history")
        {
            if (!empty($request->input('filter_products'))) {
                $jobcard = $rows1->where('job_card.product_id', $request->input('filter_products'));
                $filter_details['filter_products']=$request['filter_products'];
            }
        }

        $filter_details['filter_globalsearch']='';
        if (!empty($request->has('filter_globalsearch'))) {
            $searchQuery =  $request->input('filter_globalsearch');
            if($type=="history")
            {
                $jobcard =$rows1->where(function ($q) use ($searchQuery) {
                    $q->Where('customers.name', 'LIKE', '%' .$searchQuery. '%')
                    ->orWhere('customers.contact_number', 'LIKE', '%' . $searchQuery. '%')
                    ->orWhere('products.name', 'LIKE',  '%' .$searchQuery. '%')
                    ->orWhere('job_card.jobcard_number', 'LIKE',  '%' .$searchQuery. '%')
                    ->orWhere('job_card.date', 'LIKE', '%' . $searchQuery. '%')
                    ->orWhere('status.name', 'LIKE',  '%' .$searchQuery. '%')
                    ->orWhere('jobcard_bills.received_amount', 'LIKE',  '%' .$searchQuery. '%');
                });
            }else if($type=="report")
            {
                $jobcard =$rows1->where(function ($q) use ($searchQuery) {
                    $q->Where('customers.name', 'LIKE', '%' .$searchQuery. '%')
                    ->orWhere('customers.contact_number', 'LIKE', '%' . $searchQuery. '%')
                    ->orWhere('job_card.jobcard_number', 'LIKE',  '%' .$searchQuery. '%')
                    ->orWhere('job_card.date', 'LIKE', '%' . $searchQuery. '%')
                    ->orWhere('jobcard_bills.received_amount', 'LIKE',  '%' .$searchQuery. '%');
                });
            }
            $filter_details['filter_globalsearch']=$request['filter_globalsearch'];
        }

        $jobcard = $rows1->paginate(Session::get('paginate'));

        return $jobcard;
    }
    public function filter_history(Request $request)
    {
        $type=$request['pageid'];
         $jobcard=$this->load_filter_results($request,$type);

        $filter_details['filter_fromdate']=$request['filter_fromdate'];
        $filter_details['filter_todate']=$request['filter_todate'];
        $filter_details['filter_status']=$request['filter_status'];
        $filter_details['filter_products']=$request['filter_products'];
        $filter_details['filter_globalsearch']=$request['filter_globalsearch'];
        $products = array();
        if (Session::get('logged_user_type') == '3') {
            $products = $this->product_list_query(Session::get('logged_vendor_id'));
        } else if (Session::get('logged_user_type') == '1') {
            $products = array();//$this->product_list_query($jobcard_cust[0]->vendor_id);
        }
        $jobcard_status = DB::table('vendor_status')
        ->select('vendor_status.status_id as id','status.name')
        ->join('status','status.id','=','vendor_status.status_id')
        ->where('vendor_status.vendor_id', '=', Session::get('logged_vendor_id'))
        ->where('vendor_status.active', '=', 'Y')
        ->where('vendor_status.ending_status', '=', '1')
        ->get();
        if($type=='history')
        {
            return view('jobcard.jobcard_history', compact('jobcard','jobcard_status','products','filter_details'));
        }else if($type=='report'){
            //return Redirect('jobcard_report')->with('jobcard', $jobcard);
            return view('jobcard.jobcard_report', compact('jobcard', 'filter_details'));
        }

    }
    public function jobcard_history_pageview(Request $request, $id)
    {
        $jobcard_cust = array();
        // DB::enableQueryLog();
        $jobcard_cust = Jobcard::select('products.name as pdtname', 'products.id as pdtid', 'job_card.remarks', 'customers.name', 'job_card.jobcard_number', 'customers.contact_number as mobile', 'job_card.vendor_id', 'cart.jobcard_reference') //DB::table('job_card')
            ->join('cart', 'cart.jobcard_reference', '=', 'job_card.jobcard_number')
            ->join('customers', 'customers.id', '=', 'job_card.customer_id')
            ->join('products', 'products.id', '=', 'job_card.product_id')
            ->where('job_card.id', '=', $id)
            ->get();
        // dd(DB::getQueryLog());
        Session::put('jobcard_reference', $jobcard_cust[0]->jobcard_reference);
        $products = array();
        if (Session::get('logged_user_type') == '3') {
            $products = $this->product_list_query(Session::get('logged_vendor_id'));
        } else if (Session::get('logged_user_type') == '1') {
            $products = $this->product_list_query($jobcard_cust[0]->vendor_id);
        }
        $product_id = $jobcard_cust[0]->pdtid;

        $productservice_id = Session::get('Products');
        $Generalservice_id = Session::get('General');
        $product_service = DB::table('service')
            ->select('service.*')
            ->where('service.type', '=', $productservice_id)
            ->where('service.product_id', '=', $product_id)
            ->get();
        $general_service = DB::table('service')
            ->select('service.*')
            ->where('service.type', '=', $Generalservice_id)
            ->get();

        $servicelist = $this->service_full_listedit($jobcard_cust[0]->jobcard_reference);

        $serviceids = Cart::where('cart.jobcard_reference', '=', $jobcard_cust[0]->jobcard_reference)
            ->pluck('cart.service_id')->toArray();

        $vendor_current_status = StatusChangeHistory::select('vendor_status.display_order', 'status.name as stname', 'status.id as id')
            ->join('status', 'status_change_history.to_status', '=', 'status.id')
            ->join('vendor_status', 'vendor_status.status_id', '=', 'status.id')
            ->where('status_change_history.jobcard_number', '=', $jobcard_cust[0]->jobcard_reference)
            ->orderBy('status_change_history.created_at', 'DESC')
            ->get();


        $vendor_status = DB::table('vendor_status')
            ->select('status.name', 'status.id','vendor_status.ending_status')
            ->join('status', 'status.id', '=', 'vendor_status.status_id')
            ->where('vendor_status.vendor_id', '=', Session::get('logged_vendor_id'))
            ->where('vendor_status.display_order', '>', $vendor_current_status[0]->display_order)
            ->orderBy('vendor_status.display_order', 'ASC')
            ->get();

        $jobcard_bills = DB::table('jobcard_bills')
            ->select('*')
            ->where('jobcard_number', '=',$jobcard_cust[0]->jobcard_reference)
            ->get();

        $statuschangehistory = DB::table('status_change_history')
            ->select('status_change_history.date','status.name as name')
            ->join('status', 'status.id', '=', 'status_change_history.to_status')
            ->where('jobcard_number', '=',$jobcard_cust[0]->jobcard_reference)
            ->get();
        $vendor_partslist = array();
        if (Session::get('Parts_status') == 'Y') {
            $vendor_partslist = DB::table('service')
                ->select('service.id', 'service.name', 'service_pricedetails.actual_price')
                ->join('service_pricedetails', 'service_pricedetails.service_id', '=', 'service.id')
                ->where('service.type', '=', Session::get('Parts'))
                ->get();
        }

        return view('jobcard.jobcard_history_view', compact('jobcard_cust', 'id', 'vendor_partslist', 'products', 'serviceids', 'general_service', 'product_service', 'servicelist', 'vendor_status', 'vendor_current_status','jobcard_bills','statuschangehistory'));
    }

    public function jobcard_report_view(Request $request)
    {
        if(strpos(Session::has('jobcard_reference'),"Temp-") === true){


            if (Session::has('jobcard_reference')) {
                $jobcard = Jobcard::firstOrFail()->where('jobcard_number','like', Session::get('jobcard_reference').'%');
                $saved = $jobcard->delete($jobcard);

                $jobcard = Cart::firstOrFail()->where('jobcard_reference','like',  Session::get('jobcard_reference').'%');
                $saved = $jobcard->delete($jobcard);

                $jobcard = StatusChange::firstOrFail()->where('jobcard_number','like', Session::get('jobcard_reference').'%');
                $saved = $jobcard->delete($jobcard);

                // $jobcard = Customer::firstOrFail()->where('id', $request['customerid']);
                // $saved = $jobcard->delete($jobcard);
            }
        }
        $products = array();
        if (Session::get('logged_user_type') == '3') {
            $products = $this->product_list_query(Session::get('logged_vendor_id'));
        } else if (Session::get('logged_user_type') == '1') {
            $products =array();// $this->product_list_query($jobcard_cust[0]->vendor_id);
        }
        $jobcard_status = DB::table('vendor_status')
        ->select('vendor_status.status_id as id','status.name')
        ->join('status','status.id','=','vendor_status.status_id')
        ->where('vendor_status.vendor_id', '=', Session::get('logged_vendor_id'))
        ->where('vendor_status.active', '=', 'Y')
        ->where('vendor_status.ending_status', '=', '1')
        ->get();
        Session::forget('jobcard_reference');
        Session::forget('customerid');
        $status_list = DB::table('vendor_status')
            //->select('vendor_status.status_id')
            ->where('vendor_status.vendor_id', '=', Session::get('logged_vendor_id'))
            ->where('vendor_status.active', '=', 'Y')
            ->where('vendor_status.ending_status', '=', '0')
            ->pluck('vendor_status.status_id')->toArray();
        $filter_details['filter_fromdate']="";
        $filter_details['filter_todate']="";
        $filter_details['filter_status']="";
        $filter_details['filter_products']="";
        $filter_details['filter_globalsearch']='';
        $jobcard = array();
        $jobcard=$this->load_filter_results($request,'report');

        return view('jobcard.jobcard_report', compact('jobcard','jobcard_status','products','filter_details'));
    }
    public function jobcard_reviews_view(Request $request)
    {
        if(strpos(Session::has('jobcard_reference'),"Temp-") === true){


            if (Session::has('jobcard_reference')) {
                $jobcard = Jobcard::firstOrFail()->where('jobcard_number','like', Session::get('jobcard_reference').'%');
                $saved = $jobcard->delete($jobcard);

                $jobcard = Cart::firstOrFail()->where('jobcard_reference','like',  Session::get('jobcard_reference').'%');
                $saved = $jobcard->delete($jobcard);

                $jobcard = StatusChange::firstOrFail()->where('jobcard_number','like', Session::get('jobcard_reference').'%');
                $saved = $jobcard->delete($jobcard);

            }
        }
        Session::forget('jobcard_reference');
        Session::forget('customerid');
        //  $jobcard = array();
        // $rows1 = Reviews::leftjoin('job_card', 'job_card.jobcard_number', '=', 'reviews.jobcard_number')
        //     ->leftjoin('customers', 'customers.id', '=', 'job_card.customer_id')
        //     ->leftjoin('cart', 'cart.jobcard_reference', '=', 'job_card.jobcard_number')
        //     ->select('job_card.id as rid','reviews.date as rdate', \DB::raw("GROUP_CONCAT(cart.service_name) as service_name"), 'reviews.jobcard_number as jobcard_number', 'customers.name as custname',  'customers.contact_number as custmobile','reviews.star_rating','reviews.review');
        // $jobcard = $rows1->paginate(Session::get('paginate'));

        $filter_details['filter_fromdate']="";
        $filter_details['filter_todate']="";
       // $filter_details['filter_status']="";
       // $filter_details['filter_products']="";
        $filter_details['filter_globalsearch']='';
        $jobcard = array();
        $jobcard=$this->load_filter_reviews($request);

        //     DB::enableQueryLog(); filter_todate filter_status filter_products

        return view('jobcard.jobcard_reviews', compact('jobcard','filter_details'));
    }
    public function load_filter_reviews(Request $request)
    {
        $jobcard = array();
        $rows1 = Reviews::leftjoin('job_card', 'job_card.jobcard_number', '=', 'reviews.jobcard_number')
            ->leftjoin('customers', 'customers.id', '=', 'job_card.customer_id')
            ->leftjoin('cart', 'cart.jobcard_reference', '=', 'job_card.jobcard_number')
            ->select('job_card.id as rid','reviews.date as rdate', \DB::raw("GROUP_CONCAT(cart.service_name) as service_name"), 'reviews.jobcard_number as jobcard_number', 'customers.name as custname',  'customers.contact_number as custmobile','reviews.star_rating','reviews.review');
            $filter_details=array();
            $filter_details['filter_fromdate']="";
            $filter_details['filter_todate']="";
            if (!empty($request->input('filter_fromdate')) && !empty($request->input('filter_todate'))) {
                $dfrom=date("Y-m-d",strtotime($request['filter_fromdate']));
                $dto=date("Y-m-d",strtotime($request['filter_todate']));
                $rows1->whereBetween('reviews.date', [$dfrom, $dto]);
                $filter_details['filter_fromdate']=$request['filter_fromdate'];
                $filter_details['filter_todate']=$request['filter_todate'];
            }
            if (!empty($request->input('filter_fromdate')) && empty($request->input('filter_todate'))) {
                $dfrom=date("Y-m-d",strtotime($request['filter_fromdate']));
                $dto=date("Y-m-d");
                $rows1->whereBetween('reviews.date', [$dfrom, $dto]);
                $filter_details['filter_fromdate']=$request['filter_fromdate'];
                $filter_details['filter_todate']="";
            }
            if (empty($request->input('filter_fromdate')) && !empty($request->input('filter_todate'))) {
                $dfrom=date("Y-m-d",strtotime($request['filter_todate']));
                $dto=date("Y-m-d",strtotime($request['filter_todate']));
                $rows1->whereBetween('reviews.date', [$dfrom, $dto]);
                $filter_details['filter_fromdate']="";
                $filter_details['filter_todate']=$request['filter_todate'];
            }
            $filter_details['filter_globalsearch']='';
            if (!empty($request->has('filter_globalsearch'))) {
                $searchQuery =  $request->input('filter_globalsearch');
                    $jobcard =$rows1->where(function ($q) use ($searchQuery) {
                        $q->Where('customers.name', 'LIKE', '%' .$searchQuery. '%')
                        ->orWhere('customers.contact_number', 'LIKE', '%' . $searchQuery. '%')
                        ->orWhere('job_card.jobcard_number', 'LIKE',  '%' .$searchQuery. '%')
                        ->orWhere('job_card.date', 'LIKE', '%' . $searchQuery. '%');
                    });



                $filter_details['filter_globalsearch']=$request['filter_globalsearch'];
            }

            $jobcard = $rows1->paginate(Session::get('paginate'));
        return $jobcard;
    }
    public function filter_review(Request $request)
    {

         $jobcard=$this->load_filter_reviews($request);

        $filter_details['filter_fromdate']=$request['filter_fromdate'];
        $filter_details['filter_todate']=$request['filter_todate'];
       // $filter_details['filter_status']=$request['filter_status'];
        //$filter_details['filter_products']=$request['filter_products'];
        $filter_details['filter_globalsearch']=$request['filter_globalsearch'];


            return view('jobcard.jobcard_reviews', compact('jobcard','filter_details'));


    }
    public function export(Request $request)
    {
      //return Excel::download(new JobcardReportExport($request), 'jobcard-report.xls');

    }
    public function view_jobcard_fromemail($jobcard,$vendor_id,$taxenabled)
    {
        $statuslist = DB::table('vendor_status')
        ->select('vendor_status.status_id as id')
        ->where('vendor_status.vendor_id', '=', $vendor_id)
        ->where('vendor_status.display_order', '=', '1')
        ->get();
        $status = $statuslist[0]->id;
        $data_email=$this->jobcard_email($jobcard,$status,$vendor_id,'email_view',$taxenabled);
        return view('default.jobcard_details_email_view',compact('data_email'));
    }
    public function view_rating_fromemail($jobcard)
    {
        $check_rating=Jobcard::select('*')->where('review','=',null)
        ->Where('star_rating', '=',null)
        ->where('jobcard_number','=',$jobcard)
        ->get();
        if(count($check_rating)>0)
        {
            return view('default.customer_rating_email',compact('jobcard'));
        }else
        {
            $message="Already Submited.............";
            return view('default.thanku',compact('message'));
        }

    }
    public function submit_rating(Request $request)
    {
        // jobcard
        $data['star_rating']                   = $request['starrating_input'];
        $data['review']         = $request['remark'];

        $jobcard = Jobcard::firstOrFail()->where('jobcard_number', $request['jobcard']);
        $saved = $jobcard->update($data);
        $message="Thank U for Your Response.............";
        return view('default.thanku',compact('message'));
    }
}
