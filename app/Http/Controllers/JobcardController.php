<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobcard;
use App\Vendor;
use Response;
use App\Product;
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

        $jobcard->product_id               =$request['product_list'];
        $jobcard->jobcard_number               =$request['jobcardnumber'];
        $jobcard->date               =date("Y-m-d");
        $saved=$jobcard->save();
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
        ->join('products', 'products.id', '=', 'job_card.product_id')
        ->select('vendor.name as vname','products.name as pdtname' ,'job_card.id as jobid','job_card.*')
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



        return view('jobcard.jobcard', compact('jobcard'));
        //return view('snippets/salary_report_tile')->with(['staff_data' => $staff_data, 'pagination' => '']);
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
    public function jobcard_add(Request $request)
    {
        $products=array();
        if(Session::get('logged_user_type') =='3')
        {
        $products=$this->product_list_query(Session::get('logged_vendor_id'));
        }
        return view('jobcard.jobcard_add', compact('products'));
    }
    public function getProductList(Request $request)
    {
        $productlist=$this->product_list_query($request['vendor_id']);
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
            //'vendor_name' => 'required|string|max:50',
            'jobcardnumber' => 'required|string|max:50',
        ], [
            'product_list.required' => 'Product List is required',
            //'vendor_name.required' => 'Vendor List is required',
            'jobcardnumber.required' => 'JobCard Number is required'
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

        return view('jobcard.jobcard_edit',compact('jobcard','id','products'));
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
}
