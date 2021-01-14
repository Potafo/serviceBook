<?php

namespace App\Http\Controllers;

use Response;
use DB;
use Session;
use Illuminate\Http\Request;
use App\VendorCategory;
use App\VendorType;
use App\ServiceType;
use App\Status;
use App\VendorStatus;
use App\VendorServiceType;
use App\Configuration;
use App\VendorConfiguration;
use Illuminate\Support\Facades\Validator;

class VendorCategoryController extends Controller
{
    //
    public function vendorcategory_view($mode)
    {
        $category='';
        if($mode=="category"){
            $category=DB::table('vendor_category')
            ->select('vendor_category.*')
            ->orderBy('vendor_category.name', 'ASC')
            ->paginate(Session::get('paginate'));
        }else if($mode=="type"){
            $category=DB::table('vendor_type')
            ->select('vendor_type.*')
            ->orderBy('vendor_type.name', 'ASC')
            ->paginate(Session::get('paginate'));
        }else if($mode=="service_type"){
            $category=array();
            // $category=DB::table('service_type')
            // ->select('service_type.*')
            // ->orderBy('service_type.name', 'ASC')
            // ->paginate(Session::get('paginate'));
        }else if($mode=="status"){
           // DB::enableQueryLog();
           if(!empty(Session::get('logged_vendor_id')))
           {
            $category=DB::table('vendor_status')
            ->join('status','status.id','=','vendor_status.status_id')
            //->join('vendor','vendor.id','=','vendor_status.vendor_id')
            ->select('vendor_status.*','status.name')
            ->where('vendor_status.vendor_id','=',Session::get('logged_vendor_id'))
            ->orderBy('vendor_status.display_order', 'ASC')
            ->paginate(Session::get('paginate'));
           }elseif(!empty(Session::get('status_vendor')))
           {
            $category=DB::table('vendor_status')
            ->join('status','status.id','=','vendor_status.status_id')
            ->join('vendor','vendor.id','=','vendor_status.vendor_id')
            ->select('vendor_status.*','status.name','vendor.name as vname')
            ->where('vendor_status.vendor_id','=',Session::get('status_vendor'))
            ->orderBy('vendor_status.display_order', 'ASC')
            ->paginate(Session::get('paginate'));
           }else{
            $category=DB::table('vendor_status')
            ->join('status','status.id','=','vendor_status.status_id')
            ->join('vendor','vendor.id','=','vendor_status.vendor_id')
            ->select('vendor_status.*','status.name','vendor.name as vname')
            //->where('vendor_status.vendor_id','=',Session::get('status_vendor'))
            ->orderBy('vendor_status.display_order', 'ASC')
            ->paginate(Session::get('paginate'));
           }

            //dd(DB::getQueryLog());
        }

        return view('vendors.vendor_category',compact('mode','category'));

    }
    public function vendorcategory_add($mode)
    {
        $category='';
        // if($mode=="category"){
        //     $category=DB::table('vendor_category')
        //     ->select('vendor_category.*')
        //     ->orderBy('vendor_category.name', 'ASC')
        //     ->paginate(Session::get('paginate'));
        // }else if($mode=="type"){
        //     $category=DB::table('vendor_type')
        //     ->select('vendor_type.*')
        //     ->orderBy('vendor_type.name', 'ASC')
        //     ->paginate(Session::get('paginate'));
        // }else if($mode=="service_type"){
        //     $category=DB::table('service_type')
        //     ->select('service_type.*')
        //     ->orderBy('service_type.name', 'ASC')
        //     ->paginate(Session::get('paginate'));
        // }else if($mode=="status"){
        //     $category=DB::table('vendor_status')
        //     ->join('status','status.id','=','vendor_status.vendor_id')
        //     ->select('vendor_status.*')
        //     ->where('vendor_status.vendor_id','=',Session::get('logged_vendor_id'))
        //     ->orderBy('vendor_status.display_order', 'ASC')
        //     ->paginate(Session::get('paginate'));
        // }
        $servicecategory='';
        if($mode=="status"){
           //DB::enableQueryLog(); dd(DB::getQueryLog());
           if(!empty(Session::get('logged_vendor_id')))
           {
            $category=DB::table('status')
            ->whereNotIn('id', DB::table('vendor_status')->where('vendor_id', Session::get('logged_vendor_id'))->pluck('status_id')->toArray())
            ->select('status.*')
            ->get();
           }else{
            $category=DB::table('status')
            ->whereNotIn('id', DB::table('vendor_status')->where('vendor_id', Session::get('status_vendor'))->pluck('status_id')->toArray())
            ->select('status.*')
            ->get();
           }

           // dd(DB::getQueryLog());
        }else if($mode=="service_type"){
                $category=DB::table('service_type')
                ->select('service_type.*')
                ->paginate(Session::get('paginate'));
                $servicecategory=DB::table('service_category')
                ->select('service_category.*')
                ->get();
            }

        return view('vendors.vendor_category_add',compact('mode','category','servicecategory'));

    }
    public function insert(Request $request)
    {
        $mode=$request['hidden_mode'];
        $message="";
        if($mode=="category"){
            $message="Vendor Category Added Successfully";
        }else if($mode=="type"){
            $message="Vendor Type Added Successfully";
        }else if($mode=="service_type"){
            $message="Service Type Added Successfully";
        }else if($mode=="status"){
            $message="Vendor Status Added Successfully";
        }
        if(($mode=="category")  || ($mode=="type"))
        {
            $validator = Validator::make($request->all(), [
                'cat_name' => 'required|string|max:50',

            ], [
                'cat_name.required' => 'A name is required'

              ]);
        }elseif($mode=="status")
        {
            if($request['cat_name']=="" )
            {
                $validator = Validator::make($request->all(), [
                    'cat_name' => 'required|string|max:50',
                    'displayorder' => 'required|string|max:50',
                ], [
                    'cat_name.required' => 'Name is required',
                    'displayorder.required' => 'Display Order is required'
                  ]);
                if($request['cat_status']=="")
                {
                    $validator = Validator::make($request->all(), [
                        'cat_name' => 'required|string|max:50',
                        'cat_status' => 'required|string|max:50',
                        'displayorder' => 'required|string|max:50',
                    ], [
                        'cat_status.required' => 'Status is required',
                        'cat_name.required' => 'Name is required',
                        'displayorder.required' => 'Display Order is required'
                      ]);
                }else{
                    $validator = Validator::make($request->all(), [
                        'displayorder' => 'required|string|max:50',
                    ], [
                        'displayorder.required' => 'Display Order is required'

                      ]);
                }

            }else{
                $validator = Validator::make($request->all(), [
                    'displayorder' => 'required|string|max:50',
                ], [
                    'displayorder.required' => 'Display Order is required'

                  ]);
            }
            // $validator = Validator::make($request->all(), [
            //     'cat_name' => 'required|string|max:50',
            //     'status' => 'required|string|max:50',
            //     'send_email' => 'required|string|max:50',
            //     'send_sms' => 'required|string|max:50',
            //     'ending_status' => 'required|string|max:50',
            //     'displayorder' => 'required|string|max:50',
            // ], [
            //     'cat_name.required' => 'A name is required',
            //     'status.required' => 'Status is required',
            //     'send_email.required' => 'Email is required',
            //     'send_sms.required' => 'Sms is required',
            //     'ending_status.required' => 'Ending status is required',
            //     'displayorder.required' => 'Display Order is required'

            //   ]);

        }elseif($mode=="service_type")
        {
            if($request['cat_name']=="" )
            {
                $validator = Validator::make($request->all(), [
                    'cat_name' => 'required|string|max:50',
                    'vendor_name' => 'required|string|max:50',
                ], [
                    'cat_name.required' => 'Name is required',
                    'vendor_name.required' => 'Display Order is required'
                  ]);
                if($request['cat_status']=="")
                {
                    $validator = Validator::make($request->all(), [
                        'cat_name' => 'required|string|max:50',
                        'cat_status' => 'required|string|max:50',
                        'vendor_name' => 'required|string|max:50',
                    ], [
                        'cat_status.required' => 'Status is required',
                        'cat_name.required' => 'Name is required',
                        'vendor_name.required' => 'Display Order is required'
                      ]);
                }else{
                    $validator = Validator::make($request->all(), [
                        'vendor_name' => 'required|string|max:50',
                    ], [
                        'vendor_name.required' => 'Display Order is required'

                      ]);
                }

            }else{
                $validator = Validator::make($request->all(), [
                    'vendor_name' => 'required|string|max:50',
                ], [
                    'vendor_name.required' => 'Display Order is required'

                  ]);
            }
            // $validator = Validator::make($request->all(), [
            //     'cat_name' => 'required|string|max:50',
            //     'vendor_name' => 'required|string|max:50',

            // ], [
            //     'cat_name.required' => 'A name is required',
            //     'vendor_name.required' => 'Vendor name is required'

            //   ]);
        }

              if($validator->fails()) {
                $errors = $validator->errors();
                return Redirect()->back()->with('errors',$errors)->withInput($request->all());

                }else {
                    $this->insert_vendorcategory($request);
                    return Redirect('vendor_category/'.$request['hidden_mode'])->with('status', $message);
                }


    }
    public function insert_vendorcategory(Request $request)
    {
        $savestatus=0;
        $mode=$request['hidden_mode'];
        if($mode=="category")
        {
           $vcat= new VendorCategory();
            $vcat->name               =$request['cat_name'];
            $saved=$vcat->save();
            if ($saved) {
                $savestatus++;
            }
        }elseif($mode=="type") {
            $vcat= new VendorType();
            $vcat->name               =$request['cat_name'];
            $saved=$vcat->save();
            if ($saved) {
                $savestatus++;
            }
        }elseif($mode=="service_type") {
            $insertedId='';
            if($request['cat_name']!='')
            {
                $vcat= new ServiceType();
                $vcat->name               =$request['cat_name'];
                $saved=$vcat->save();
                $lastid=$vcat->id;

                //update to configuration and vendor config table

                // $config= new Configuration();
                // $config->type           =3;
                // $config->config_name    =$request['cat_name'];
                // //$config->value          =$request['config_value'];
                // $config->status         ='Y';
                // $config->page_view      ='Y';
                // $config->input_type     ='checkbox';
                // $saved=$config->save();

                //     $fieldname = strtolower(str_replace(" ", "_", $request['cat_name']));
                //     $sql = "ALTER TABLE  `vendor_configuration` ADD  $fieldname varchar(100) default 'N';";
                //     DB::select($sql);


                //ends

            }else{
                $lastid=$request['cat_status'];

            }


            $vserv_type= new VendorServiceType();
            $vserv_type->service_type=$lastid;
            $vserv_type->vendor_id=$request['vendor_name'];
           $vserv_type->service_category=$request['serv_cat'];
            $saved=$vserv_type->save();
            if ($saved) {
                $savestatus++;
            }
        }elseif($mode=="status") {
            $insertedId='';
            if($request['cat_name']!='')
            {
                $status=new Status();
                $status->name=$request['cat_name'];
                $status->save();
                $insertedId = $status->id;
            }else{
                $insertedId=$request['cat_status'];
            }


            $vcat= new VendorStatus();
            $vcat->status_id                        =$insertedId;
            $vcat->send_sms                =$request['send_sms'];
            $vcat->send_email                =$request['send_email'];
            $vcat->ending_status                =$request['ending_status'];
            $vcat->display_order               =$request['displayorder'];
            if(!empty(Session::get('logged_vendor_id')))
           {
            $vcat->vendor_id               =Session::get('logged_vendor_id');
           }else{
            $vcat->vendor_id               =Session::get('status_vendor');
           }

            $saved=$vcat->save();
            if ($saved) {
                $savestatus++;
            }
        }
        if($savestatus>0){
            $status = 'success';
           }else {
            $status = 'fail';
           }

            $response_code = '200';
            return response::json(['status' =>$status,'response_code' =>$response_code]);

    }
    public function vendor_category_edit($mode,$id)
    {
        $category='';
        if($mode=="category")
        {
            $vendor=DB::table('vendor_category')
            ->where('vendor_category.id','=',$id)
            ->select('vendor_category.*')
            ->get();
        }elseif($mode=="type"){
            $vendor=DB::table('vendor_type')
            ->where('vendor_type.id','=',$id)
            ->select('vendor_type.*')
            ->get();
        }elseif($mode=="service_type"){
            $vendor=DB::table('vendor_servicetype')
            ->join('service_type','service_type.id','=','vendor_servicetype.service_type')
            ->where('vendor_servicetype.id','=',$id)
            ->select('vendor_servicetype.*','service_type.name as name')
            ->get();
            $category=DB::table('service_category')
            ->select('service_category.*')
            ->get();
        }elseif($mode=="status"){



            if(!empty(Session::get('logged_vendor_id')))
            {
                $vendor=DB::table('vendor_status')
                ->join('status','status.id','=','vendor_status.status_id')
                ->select('vendor_status.*','status.name')
                ->where('vendor_status.vendor_id','=',Session::get('logged_vendor_id'))
                ->where('vendor_status.id','=',$id)
                ->orderBy('vendor_status.display_order', 'ASC')
                ->paginate(Session::get('paginate'));
                $category=DB::table('status')
                ->whereNotIn('id', DB::table('vendor_status')->where('vendor_id', Session::get('logged_vendor_id'))->pluck('status_id')->toArray())
                ->select('status.*')
                ->get();
            }elseif(!empty(Session::get('status_vendor'))){
                $vendor=DB::table('vendor_status')
                ->join('status','status.id','=','vendor_status.status_id')
                ->select('vendor_status.*','status.name')
                ->where('vendor_status.vendor_id','=',Session::get('status_vendor'))
                ->where('vendor_status.id','=',$id)
                ->orderBy('vendor_status.display_order', 'ASC')
                ->paginate(Session::get('paginate'));
                $category=DB::table('status')
           ->whereNotIn('id', DB::table('vendor_status')->where('vendor_id', Session::get('status_vendor'))->pluck('status_id')->toArray())
                ->select('status.*')
                ->get();
            }else{
                $vendor=DB::table('vendor_status')
                ->join('status','status.id','=','vendor_status.status_id')
                ->select('vendor_status.*','status.name')
               // ->where('vendor_status.vendor_id','=',Session::get('status_vendor'))
                ->where('vendor_status.id','=',$id)
                ->orderBy('vendor_status.display_order', 'ASC')
                ->paginate(Session::get('paginate'));
                $category=DB::table('status')
           ->whereNotIn('id', DB::table('vendor_status')->where('vendor_id', Session::get('status_vendor'))->pluck('status_id')->toArray())
                ->select('status.*')
                ->get();
            }

        }


        //dd(DB::getQueryLog());
        return view('vendors.vendor_category_edit',compact('mode','vendor','id','category'));
    }
    public function update(Request $request)
    {
        $mode=$request['hidden_mode'];
        $message="";
        if($mode=="category"){
            $message="Vendor Category Updated Successfully";
        }else if($mode=="type"){
            $message="Vendor Type Updated Successfully";
        }else if($mode=="service_type"){
            $message="Service Type Updated Successfully";
        }else if($mode=="status"){
            $message="Vendor Status Updated Successfully";
        }
      $updated = $this->update_sql($request);
        if($updated == '2'){
            return Redirect('vendor_category/'.$request['hidden_mode'])->with('status', $message);
        }else{
            return Redirect('vendor_category/'.$request['hidden_mode'])->with('status', 'Sorry!');
        }

    }
    public function update_sql(Request $request)
    {
        $mode=$request['hidden_mode'];
        $savestatus=1;
        if($mode=="category")
        {
            $data['name']              =$request['cat_name'];
            $data['status']              =$request['status'];
            $vendor = VendorCategory::findOrFail($request['hidden_id']);
            $saved=$vendor->update($data);
            if ($saved) {
                $savestatus++;
            }
        }else if($mode=="type")
        {
            $data['name']              =$request['cat_name'];
            $data['status']              =$request['status'];
            $vendor = VendorType::findOrFail($request['hidden_id']);
            $saved=$vendor->update($data);
            if ($saved) {
                $savestatus++;
            }
        }else if($mode=="service_type")
        {
            // $data['name']              =$request['cat_name'];
            // $data['status']              =$request['status'];
            // $vendor = ServiceType::findOrFail($request['hidden_id']);
            // $saved=$vendor->update($data);

            $data['vendor_id']              =$request['vendor_name'];
           // $data['service_category']              =$request['serv_cat'];
            $data['status']              =$request['status'];
            $vendor =  VendorServiceType::findOrFail($request['hidden_id']);
             $saved=$vendor->update($data);
            if ($saved) {
                $savestatus++;
            }
        }else if($mode=="status")
        {//send_email send_sms ending_status
            //`name`, `active`, `created_at`, `updated_at`, `vendor_id`, `send_sms`, `send_email`, `display_order`, `ending_status`
            $data['name']                   =$request['cat_name'];
            $data['send_email']              =$request['send_email'];
            $data['send_sms']               =$request['send_sms'];
            $data['ending_status']              =$request['ending_status'];
            $data['display_order']              =$request['displayorder'];
            $data['active']              =$request['status'];
            $vendor = VendorStatus::findOrFail($request['hidden_id']);
            $saved=$vendor->update($data);
            if ($saved) {
                $savestatus++;
            }
        }
        return $savestatus;
    }
    public function filter_by_vendorid(Request $request)
    {
        $vendorid=$request['vendor_id'];
        $mode=$request['mode'];
        // `vendor_servicetype`(`id`, `service_type`, `vendor_id`, `status`,
        //`service_type`(`id`, `name`, `status`, `created_at`, `upd
        $filter_vendor=DB::table('vendor_servicetype as vst')
        ->join('service_type as st','st.id','=','vst.service_type')
        ->select('vst.*','st.name')
        ->where('vst.vendor_id','=',$vendorid)
        ->paginate(Session::get('paginate'));

        $append ='';
         $links='';
         if(count($filter_vendor)>0)
         {
             $i=1;
            foreach($filter_vendor as $value)
            {

                $append .= "
                <tr>
                <td class='text-center'>
                    $i
                </td>
                <td class='text-center'>
                     $value->name
                </td>
                <td class='text-center'>
                     $value->status
                </td>

                <td class='text-center'>
                <a href='../vendor_category_edit/".$mode."/".$value->id."'>  <i class='tim-icons icon-pencil'></i> </a>



                     </td>

                </tr>";
                $i++;

            }
            $links=$filter_vendor->links()->render();

         }else{
            $append .= "
            <tr> No records found </tr>";
            $links="";
         }
         return Response::json(['append' => $append,'links'=>$links]);

    }
    public function set_vendorid(Request $request)
    {
        Session::put('status_vendor',$request['vendorid']);
        return 0;
    }

}
