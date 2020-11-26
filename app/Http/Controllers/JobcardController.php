<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobcard;
use App\Vendor;
use Response;
use App\Product;
use App\Service;
use App\JobcardServices;
use App\StatusChange;
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
    public function jobcard_view(Request $request,Jobcard $model)
    {
        //$logged_user_id = Auth::id();
         $status_list=DB::table('status')
        ->select('status.*')
        ->where('status.vendor_id','=',Session::get('logged_vendor_id'))
        ->orderBy('status.display_order', 'DESC')
        ->paginate(1);

        $status_last=$status_list[0]->id;
        $page = $request['page'];
        //DB::enableQueryLog();
        //$rows1=DB::table('job_card')
        $rows1=Jobcard::join('jobcard_services','job_card.jobcard_number','=','jobcard_services.jobcard_reference')
        ->select('job_card.name as custname','job_card.jobcard_number as jcnmbr','status.name as statusname','job_card.mobile as custmobile','jobcard_services.*','products.name as pdtname',\DB::raw("GROUP_CONCAT(service.name) as sname"))
        ->leftjoin("service",\DB::raw("FIND_IN_SET(service.id,jobcard_services.generalservice) OR  FIND_IN_SET(service.id,jobcard_services.productservice)"),">",\DB::raw("'0'"))
        ->join('products', 'products.id', '=', 'jobcard_services.product_id')
        ->join('status', 'status.id', '=', 'jobcard_services.current_status')
        ->where('jobcard_services.current_status','!=',$status_last)
         ->groupBy('jobcard_number')
         ->orderBy('created_at','DESC');
        $jobcard=array();
        if(Session::get('logged_user_type') =='3')
        {
            $vendor_id=Session::get('logged_vendor_id');
            $jobcard= $rows1->where('job_card.vendor_id','=',$vendor_id);
        }
        else if(Session::get('logged_user_type') =='1')
        {
        }
        $jobcard=$rows1->paginate(5);
       // $jobcard=Jobcard::paginate(5);
        //dd(DB::getQueryLog());
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
        $jobcard_cust=array();
        $jobcard_cust=Jobcard::select('job_card.name','jobcard_services.jobcard_number','job_card.mobile','jobcard_services.jobcard_reference','job_card.vendor_id')//DB::table('job_card')
        ->join('jobcard_services','jobcard_services.jobcard_reference','=','job_card.jobcard_number')
        ->where('jobcard_services.id','=',$id)
        ->get();
        Session::put('jobcard_reference',$jobcard_cust[0]->jobcard_reference);
        $products=array();
        if(Session::get('logged_user_type') =='3')
        {
        $products=$this->product_list_query(Session::get('logged_vendor_id'));
        }else if(Session::get('logged_user_type') =='1')
        {
        $products=$this->product_list_query($jobcard_cust[0]->vendor_id);
        }
        $general_service= DB::table('service')
        ->select('service.*')
        ->where('service.type','=','1')
        ->get();
        $product_service= DB::table('service')
        ->select('service.*')
        ->where('service.type','=','2')
        ->get();
        return view('jobcard.jobcard_edit',compact('jobcard_cust','id','products','general_service','product_service'));
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
        $validator=$this->validate_data($request);
        if($validator->fails()) {
        return Response::json(['errors' => $validator->errors()]);
     }else {
            $jobcard_reference=$request['jobcardnumber_ref'];
             $saved=$this->insert_jobcardservices($request);
             return Response::json(['success' => 1]);
     }
    }
    public function insert_jobcardservices(Request $request)
    {
        $savestatus=0;
        //`jobcard_reference`, `jobcard_number`, `product_id`, `generalservice`, `productservice`,
        $jobcardnumber=Session::get('logged_vendor_shortcode').mt_rand(1000000,99999999);
        $jobcard_servcs= new JobcardServices();
        $jobcard_servcs->jobcard_reference               =$request['jobcardnumber_ref'];
        $jobcard_servcs->jobcard_number               =$jobcardnumber;
        $jobcard_servcs->product_id               =$request['product_list'];
        if(isset($request['generalservice'])){
            $generalservice=implode(',', $request['generalservice']);
            $jobcard_servcs->generalservice               =$generalservice;
        }

        if(isset($request['productservice'])){
            $productservice=implode(',', $request['productservice']);
            $jobcard_servcs->productservice               =$productservice;
        }

        //$jobcard_servcs->current_status               =1;

        $saved=$jobcard_servcs->save();

        // status updation
        // `status_change`(`id`, `jobcard_number`, `from_status`, `to_status`, `change_by`, `date`, `created_at`, `updated_at`)

        $statuslist= DB::table('status')
        ->select('status.id')
        ->where('status.vendor_id','=',Session::get('logged_vendor_id'))
        ->get();
        $status=$statuslist[0]->id;
        $statuschange= new StatusChange();
        $statuschange->jobcard_number               =$jobcardnumber;
        $statuschange->from_status               =0;
        $statuschange->to_status               =$status;
        $statuschange->change_by               =Session::get('logged_vendor_id');
        $statuschange->date                     =date('Y-m-d');
        $statuschange->save();

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
    {

        //DB::enableQueryLog();

        $servicelist=JobcardServices::select('jobcard_services.*','products.id as pid','products.name as pdtname',\DB::raw("GROUP_CONCAT(service.name) as sname"))
        ->leftjoin("service",\DB::raw("FIND_IN_SET(service.id,jobcard_services.generalservice) OR  FIND_IN_SET(service.id,jobcard_services.productservice)"),">",\DB::raw("'0'"))
        ->join('products', 'products.id', '=', 'jobcard_services.product_id')
         ->where('jobcard_services.jobcard_reference','=',$request['ref'])
         ->groupBy('jobcard_number')
         ->orderBy('created_at','DESC')
         ->paginate(10);
         //dd(DB::getQueryLog());

         $append ='';
         if(count($servicelist)>0)
         {
             $i=1;
            foreach($servicelist as $value)
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
                <td>
                <a style='color: #ba54f5; cursor: pointer;'  data-toggle='modal' data-target='#productsInsert' data-type='update' data-pdtservice='".$value->productservice."' data-genservice='".$value->generalservice."' data-pdtid='".$value->pid."' data-jobcardref ='".Session::get('jobcard_reference')."' data-jobcardnmbr='".$value->jobcard_number."' data-id='".$value->id."' >
                <i class='tim-icons icon-pencil'></i>
                          </a>

                    <a style='color: #ba54f5; cursor: pointer;' data-toggle='modal' id='deleteButton' data-target='#delete_services' data-jobcardnmbr='".$value->jobcard_number."' data-id='".$value->id."' title='Delete Service'>
                    <i class='tim-icons icon-trash-simple'></i>
                </a>
                     </td>

                </tr>";
                $i++;

            }
            $links=$servicelist->links()->render();

         }
         return Response::json(['append' => $append,'links'=>$links]);
        //return $append;


    }
    public function getServiceList(Request $request)
    {
       // dd($request['gservice']);
        $product_id=$request['product_id'];
        $general_service= DB::table('service')
        ->select('service.*')
        ->where('service.type','=','2')
        ->get();
        $product_service= DB::table('service')
        ->select('service.*')
        ->where('service.type','=','1')
        ->where('service.product_id','=',$product_id)
        ->get();
        $productservice=array();
        $generalservice=array();
        $append='';
        $append.='<div class="form-group" >';
        $append.='<label>General Service</label><br>';
        $append.='<table >';
                 if(isset($request['gservice']))
                    {
                       $generalservice=explode(',', $request['gservice']);
                    }
                $i=0;
                foreach($general_service as $list)
                {
                    $checked='';
                    if (in_array($list->id, $generalservice))
                    $checked=" checked ";
                    $i++;
                    if($i==1)
                    {
                        $append.='<tr >';
                    }
                    $append.='            <td width="40%">';
                    $append.='                <div class="form-check form-check-inline">';
                    $append.='                    <label class="form-check-label">';
                    $append.='                        <input class="form-check-input" type="checkbox" name="generalservice[]" '.$checked.'  id="inlineCheckbox_gs'.$list->id.'" value="'.$list->id.'">'.$list->name;
                    $append.='                        <span class="form-check-sign"></span>';
                    $append.='                    </label>';
                    $append.='                </div>';
                    $append.='            </td>';
                                if($i%3 == 0)
                                {
                                    $append.='                </tr> <tr>';
                                }
                }
        $append.='    </table>';
    $append.='</div>';
    $append.='<br>';
    $append.='<div class="form-group" >';
        $append.='<label>Product Service</label><br>';
        $append.='<table >';
                $i=0;
                if(isset($request['pservice']))
                    {
                       $productservice=explode(',', $request['pservice']);
                    }

                foreach($product_service as $list)
                {
                    $checked='';
                    if (in_array($list->id, $productservice))
                    $checked=" checked ";

                    $i++;
                    if($i==1)
                    {
                        $append.='<tr >';
                    }
                    $append.='            <td width="40%">';
                    $append.='                <div class="form-check form-check-inline">';
                    $append.='                    <label class="form-check-label">';
                    $append.='                        <input class="form-check-input" type="checkbox" name="productservice[]" '.$checked.' id="inlineCheckbox_gs'.$list->id.'" value="'.$list->id.'">'.$list->name;
                    $append.='                        <span class="form-check-sign"></span>';
                    $append.='                    </label>';
                    $append.='                </div>';
                    $append.='            </td>';
                                if($i%3 == 0)
                                {
                                    $append.='                </tr> <tr>';
                                }
                }
                        $append.='    </table>';

                        $append.='</div>';
                        $append.='<br>';


        return $append;

    }
    public function delete($id)
    {
        $project = JobcardServices::find($id);
        return view('jobcard.jobcard_delete', compact('project'));
    }
    public function fielddelete(Request $request)
    {
        $jobcard = JobcardServices::findOrFail($request['referenceid']);
        $saved=$jobcard->delete($jobcard);
        //$request['referencenumber']);referenceid
        // $project = JobcardServices::find($id);
        if(isset($request['fromeditpage']) ==1){
            return Redirect('jobcard')->with('status', 'JobCard Deleted successfully!');
        }else{
            return Redirect('jobcard_add')->with('status', 'JobCard Deleted successfully!');
        }

    }
    public function service_update(Request $request)
    {
        $validator=$this->validate_data($request);
        if($validator->fails()) {
        return Response::json(['errors' => $validator->errors()]);
     }else {
            //$jobcard_reference=$request['jobcardnumber_ref'];
             $saved=$this->update_jobcardservices($request);
             return Response::json(['success' => 1]);
     }
    }
    public function update_jobcardservices(Request $request)
    {

        $savestatus=1;

        //$data['name']              =$request['jobcard_id'];
         //$data['product_id']           =$request['product_list'];
         //$data['vendor_id']       =$request['vendor_name'];
         if(isset($request['generalservice'])){
            $generalservice=implode(',', $request['generalservice']);
            $data['generalservice']               =$generalservice;
        }else{
            $data['generalservice']               =null;
        }

        if(isset($request['productservice'])){
            $productservice=implode(',', $request['productservice']);
            $data['productservice']              =$productservice;
        }else{
            $data['productservice']  =null;
        }

         $jobcard = JobcardServices::findOrFail($request['jobcardid_update']);

        if($data['productservice']  == null  &&  $data['generalservice']  ==null)
        {
            $saved=$jobcard->delete($data);
        }else{
            $saved=$jobcard->update($data);
        }

         if ($saved) {
             $savestatus++;
         }

    }

    public function fielddelete_each(Request $request)
    {
        $jobcard = JobcardServices::findOrFail($request['referenceid']);
        $saved=$jobcard->delete($jobcard);
        //$request['referencenumber']);referenceid
        // $project = JobcardServices::find($id);
        return Redirect('jobcard')->with('status', 'JobCard Deleted successfully!');
    }
    public function load_jobcardservice_list_edit(Request $request)
    {
        //DB::enableQueryLog();

        $servicelist=JobcardServices::select('jobcard_services.*','products.id as pid','products.name as pdtname',\DB::raw("GROUP_CONCAT(service.name) as sname"))
        ->leftjoin("service",\DB::raw("FIND_IN_SET(service.id,jobcard_services.generalservice) OR  FIND_IN_SET(service.id,jobcard_services.productservice)"),">",\DB::raw("'0'"))
        ->join('products', 'products.id', '=', 'jobcard_services.product_id')
         ->where('jobcard_services.jobcard_number','=',$request['ref'])//$request['ref'])
         ->groupBy('jobcard_number')
         ->orderBy('created_at','DESC')
         ->paginate(10);
        // dd(DB::getQueryLog());

         $append ='';

         if(count($servicelist)>0)
         {
             $i=1;
            foreach($servicelist as $value)
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
                <td>
                <a style='color: #ba54f5; cursor: pointer;' class='loadeditpage' data-toggle='modal' data-target='#productsInsert' data-type='update' data-pdtservice='".$value->productservice."' data-genservice='".$value->generalservice."' data-pdtid='".$value->pid."' data-jobcardref ='".Session::get('jobcard_reference')."' data-jobcardnmbr='".$value->jobcard_number."' data-id='".$value->id."' >
                <i class='tim-icons icon-pencil'></i>
                          </a>

                    <a style='color: #ba54f5; cursor: pointer;' data-toggle='modal' id='deleteButton' data-target='#delete_services' data-jobcardnmbr='".$value->jobcard_number."' data-id='".$value->id."' title='Delete Service'>
                    <i class='tim-icons icon-trash-simple'></i>
                </a>
                     </td>

                </tr>";
                $i++;

            }
            $links=$servicelist->links()->render();

         }
         return Response::json(['append' => ($append),'links'=>$links]);
    }
    public function jobcard_view_each(Request $request,$id)
    {
        $jobcard_cust=array();
        $jobcard_cust=Jobcard::select('job_card.name','jobcard_services.jobcard_number','job_card.mobile','jobcard_services.jobcard_reference','job_card.vendor_id')//DB::table('job_card')
        ->join('jobcard_services','jobcard_services.jobcard_reference','=','job_card.jobcard_number')
        ->where('jobcard_services.id','=',$id)
        ->get();
        Session::put('jobcard_reference',$jobcard_cust[0]->jobcard_reference);
        $products=array();
        if(Session::get('logged_user_type') =='3')
        {
        $products=$this->product_list_query(Session::get('logged_vendor_id'));
        }else if(Session::get('logged_user_type') =='1')
        {
        $products=$this->product_list_query($jobcard_cust[0]->vendor_id);
        }
        $general_service= DB::table('service')
        ->select('service.*')
        ->where('service.type','=','1')
        ->get();
        $product_service= DB::table('service')
        ->select('service.*')
        ->where('service.type','=','2')
        ->get();
        return view('jobcard.jobcard_view',compact('jobcard_cust','id','products','general_service','product_service'));
    }
    public function load_jobcardservice_list_view(Request $request)
    {
        //DB::enableQueryLog();

        $servicelist=JobcardServices::select('jobcard_services.*','products.id as pid','products.name as pdtname',\DB::raw("GROUP_CONCAT(service.name) as sname"))
        ->leftjoin("service",\DB::raw("FIND_IN_SET(service.id,jobcard_services.generalservice) OR  FIND_IN_SET(service.id,jobcard_services.productservice)"),">",\DB::raw("'0'"))
        ->join('products', 'products.id', '=', 'jobcard_services.product_id')
         ->where('jobcard_services.jobcard_number','=',$request['ref'])//$request['ref'])
         ->groupBy('jobcard_number')
         ->orderBy('created_at','DESC')
         ->paginate(10);
        // dd(DB::getQueryLog());

         $append ='';

         if(count($servicelist)>0)
         {
             $i=1;
            foreach($servicelist as $value)
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
         return Response::json(['append' => ($append),'links'=>$links]);
    }

}
