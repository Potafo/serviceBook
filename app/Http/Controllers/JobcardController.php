<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobcard;
use App\Vendor;
use Response;
use App\Product;
use App\Service;
use App\JobcardServices;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Validator;
use Session;

class JobcardController extends Controller
{
    public function insert_jobcard(Request $request)
    {
        // `job_card`(`id`, `user_id`, `vendor_id`, `jobcard_number`, `product_id`, `date`, `created_at`, `modified_at`)
        $savestatus=0;
        $jobcard= new Jobcard();
        $jobcard->user_id               =$request['user_id'];
        if(Session::get('logged_user_type') =='3')
        {
            $jobcard->vendor_id =Session::get('logged_vendor_id');
        }else if(Session::get('logged_user_type') =='1')
        {
            $jobcard->vendor_id               =$request['vendor_name'];
        }

        // $generalservice=implode(',', $request['generalservice']);
        // $productservice=implode(',', $request['productservice']);

        // $jobcard->generalservice               =$generalservice;
        // $jobcard->productservice               =$productservice;
        // $jobcard->product_id               =$request['product_list'];
         $jobcard->name               =$request['jobcard_name'];
        $jobcard->mobile               =$request['jobcard_mobile'];
        $jobcard->jobcard_number           =$request['jobcardrefnumber'];
        $jobcard->date               =date("Y-m-d");
        $saved=$jobcard->save();
        Session::forget('jobcard_reference');
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
    public function jobcard_view(Jobcard $model)
    {
        //$logged_user_id = Auth::id();
        //$jobcard=Jobcard::all();

        $rows1=DB::table('job_card')
        ->join('vendor', 'vendor.id', '=', 'job_card.vendor_id')
        //->join('products', 'products.id', '=', 'job_card.product_id')
        ->select('vendor.name as vname','job_card.id as jobid','job_card.*')
        ->orderBy('job_card.date', 'ASC');

       // $rows1=DB::table('job_card');
        $jobcard=array();
        if(Session::get('logged_user_type') =='3')
        {
            $vendor_id=Session::get('logged_vendor_id');
            $jobcard= $rows1->where('job_card.vendor_id','=',$vendor_id)
                ->paginate(5);
        }
        else if(Session::get('logged_user_type') =='1')
        {
            $jobcard=$rows1->paginate(5);
        }
        if(Session::has('jobcard_reference'))
        {
        Session::forget('jobcard_reference');
        }
       return view('jobcard.jobcard', compact('jobcard'));
        //return view('snippets/salary_report_tile')->with(['staff_data' => $staff_data, 'pagination' => '']);
    }
    public function product_list_query($vendorid)
    {
         //DB::enableQueryLog();dd(DB::getQueryLog());
         $productlist=Product::select('id','name')
         ->where('vendor_id','=',$vendorid)
         ->get();
        // dd(DB::getQueryLog());
        return $productlist;
    }
    public function jobcard_add(Request $request)
    {
        $products=array();
        if(Session::get('logged_user_type') =='3')
        {
        $products=$this->product_list_query(Session::get('logged_vendor_id'));
        }
        $general_service= DB::table('service')
        ->select('service.*')
        ->where('service.type','=','1')
        ->get();
        $product_service= DB::table('service')
        ->select('service.*')
        ->where('service.type','=','2')
        ->get();
        //Session::forget('jobcard_reference');
        if(!Session::has('jobcard_reference'))
        {
            //return "signout";Session::get('jobcard_reference')Session::get('jobcard_reference')
            $jobcard_reference=Session::get('logged_vendor_shortcode').mt_rand(1000000,99999999);
            Session::put('jobcard_reference',$jobcard_reference);
        }
        $servicelist=array();
        $servicelist=JobcardServices::select('jobcard_services.*')
        ->where('jobcard_services.jobcard_reference','=',Session::get('jobcard_reference'))
        ->paginate(5);
        //$jobcard_reference=Session::get('logged_vendor_shortcode').mt_rand(1000000,99999999);

        return view('jobcard.jobcard_add', compact('products','general_service','product_service','servicelist'));
    }
    public function getProductList(Request $request)
    {
        if(Session::get('logged_user_type') =='3')
        {
            $productlist=$this->product_list_query(Session::get('logged_vendor_id'));
        }else{
            $productlist=$this->product_list_query($request['vendor_id']);
        }

        $append='';
        if(count($productlist)>0)
        {
            foreach($productlist as $value)
            {
                $append.='<option value="'.$value['id'].'">'. $value['name'].'</option>';
            }
        }else{
            $append.='<option value="">No records</option>';
        }

        return $append;

    }//product_list vendor_name jobcardnumber
    public function validate_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_list' => 'required|string|max:50',
           // 'generalservice.*' => 'required|string|max:50',
          // 'productservice.*' => 'required|string|max:50',
        ], [
            'product_list.required' => 'Product List is required',
            //'vendor_name.required' => 'Vendor List is required',
           // 'jobcardnumber.required' => 'JobCard Number is required'
          ]);
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
        $validator=$this->validate_jobcard($request);
        if($validator->fails()) {
             $errors = $validator->errors();
             return Redirect()->back()->with('errors',$errors)->withInput($request->all());
        }else {
            $this->insert_jobcard($request);
            return Redirect('jobcard')->with('status', 'Jobcard Successfully Added!');
        }
    }
    public function jobcard_edit(Request $request,$id)
    {

        $jobcard=DB::table('job_card')
        ->select('job_card.*')
        ->where('job_card.id','=',$id)
        ->get();
        $products=array();
        if(Session::get('logged_user_type') =='3')
        {
        $products=$this->product_list_query(Session::get('logged_vendor_id'));
        }else if(Session::get('logged_user_type') =='1')
        {
        $products=$this->product_list_query($jobcard[0]->vendor_id);
        }
        $general_service= DB::table('service')
        ->select('service.*')
        ->where('service.type','=','1')
        ->get();
        $product_service= DB::table('service')
        ->select('service.*')
        ->where('service.type','=','2')
        ->get();
        return view('jobcard.jobcard_edit',compact('jobcard','id','products','general_service','product_service'));
    }

    public function update(Request $request)
    {
        $validator=$this->validate_data($request);
        if($validator->fails()) {
            $errors = $validator->errors();
            return Redirect()->back()->with('errors',$errors)->withInput($request->all());

       }else {
       $updated = $this->update_sql($request);
           return Redirect('jobcard')->with('status', 'Job card Updated successfully!');
       }

    }
    public function update_sql(Request $request)
    {
        //$vendor= new Vendor();
        $savestatus=1;

           //$data['name']              =$request['jobcard_id'];
            $data['product_id']           =$request['product_list'];
            $data['vendor_id']       =$request['vendor_name'];
            $jobcard = Jobcard::findOrFail($request['jobcard_id']);
            $saved=$jobcard->update($data);
            if ($saved) {
                $savestatus++;
            }

        return $savestatus;
    }

    public function service_insert(Request $request)
    {
        // echo "sadasd";
        // dd($request);
         $validator=$this->validate_data($request);
        if($validator->fails()) {
        //    $errors = $validator->errors();
        //   return Redirect()->back()->with('errors',$errors)->withInput($request->all());

        return Response::json(['errors' => $validator->errors()]);

     }else {


       // $jobcard_reference=Session::get('logged_vendor_shortcode').mt_rand(1000000,99999999);
       // return view('jobcard.jobcard_add', compact('products','general_service','product_service','jobcard_reference'));


            $jobcard_reference=$request['jobcardnumber_ref'];
             $saved=$this->insert_jobcardservices($request);
             return Response::json(['success' => 1]);
             if($saved==1)
             {

            //     $products=array();
            //     if(Session::get('logged_user_type') =='3')
            //     {
            //     $products=$this->product_list_query(Session::get('logged_vendor_id'));
            //     }
            //     $general_service= DB::table('service')
            //     ->select('service.*')
            //     ->where('service.type','=','1')
            //     ->get();
            //     $product_service= DB::table('service')
            //     ->select('service.*')
            //     ->where('service.type','=','2')
            //     ->get();
            //     $servicelist=JobcardServices::select('jobcard_services.*')// DB::table('jobcard_services')
            //    // ->select('jobcard_services.*')
            //     ->where('jobcard_services.jobcard_reference','=',$jobcard_reference)
            //     ->paginate(5);
            //    $status="Jobcard services Successfully Added!";
            //     return view('jobcard.jobcard_add',compact('status','products','general_service','product_service','jobcard_reference','servicelist'));

                //return Redirect()->back()->with(compact('status','products','general_service','product_service','jobcard_reference','servicelist'));
             }
              //  return Redirect()->back()
            //  ->withInput($request->only('jobcardnumber_ref'))
            //  ->with('status', 'Jobcard services Successfully Added!');
     }
    }
    public function insert_jobcardservices(Request $request)
    {
        $savestatus=0;
        //`jobcard_reference`, `jobcard_number`, `product_id`, `generalservice`, `productservice`,
        $jobcard_servcs= new JobcardServices();
        $jobcard_servcs->jobcard_reference               =$request['jobcardnumber_ref'];
        $jobcard_servcs->jobcard_number               =Session::get('logged_vendor_shortcode').mt_rand(1000000,99999999);
        $jobcard_servcs->product_id               =$request['product_list'];
        $generalservice=implode(',', $request['generalservice']);
        $productservice=implode(',', $request['productservice']);

        $jobcard_servcs->generalservice               =$generalservice;
        $jobcard_servcs->productservice               =$productservice;

        $saved=$jobcard_servcs->save();
        if ($saved) {
            $savestatus++;
        }
        if($savestatus>0){
            return 1;
        }else{
            return 0;
        }

    }
    public function load_jobcardservice_list(Request $request)
    {//DB::enableQueryLog();
        $servicelist=JobcardServices::select('jobcard_services.*','products.name as pdtname',\DB::raw("GROUP_CONCAT(service.name) as sname"))
        ->leftjoin("service",\DB::raw("FIND_IN_SET(service.id,jobcard_services.generalservice) OR  FIND_IN_SET(service.id,jobcard_services.productservice)"),">",\DB::raw("'0'"))
        ->join('products', 'products.id', '=', 'jobcard_services.product_id')
         ->where('jobcard_services.jobcard_reference','=',$request['ref'])
         ->groupBy('jobcard_number')
         ->paginate(10);
         //dd(DB::getQueryLog());
         $append ='';
         if(count($servicelist)>0)
         {
             $i=1;
            foreach($servicelist as $key=>$value)
            {

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
                </td>


                </tr>";
                $i++;

            }
            $links=$servicelist->links()->render();

         }
         return Response::json(['append' => $append,'links'=>$links]);
        //return $append;


    }
}
