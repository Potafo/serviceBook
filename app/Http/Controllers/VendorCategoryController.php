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
            ->paginate(5);
        }else if($mode=="type"){
            $category=DB::table('vendor_type')
            ->select('vendor_type.*')
            ->orderBy('vendor_type.name', 'ASC')
            ->paginate(5);
        }else if($mode=="service_type"){
            $category=DB::table('service_type')
            ->select('service_type.*')
            ->orderBy('service_type.name', 'ASC')
            ->paginate(5);
        }else if($mode=="status"){
            $category=DB::table('status')
            ->select('status.*')
            ->where('status.vendor_id','=',Session::get('logged_vendor_id'))
            ->orderBy('status.display_order', 'ASC')
            ->paginate(5);
        }

        return view('vendors.vendor_category',compact('mode','category'));

    }
    public function vendorcategory_add($mode)
    {
        $category='';
        if($mode=="category"){
            $category=DB::table('vendor_category')
            ->select('vendor_category.*')
            ->orderBy('vendor_category.name', 'ASC')
            ->paginate(5);
        }else if($mode=="type"){
            $category=DB::table('vendor_type')
            ->select('vendor_type.*')
            ->orderBy('vendor_type.name', 'ASC')
            ->paginate(5);
        }else if($mode=="service_type"){
            $category=DB::table('service_type')
            ->select('service_type.*')
            ->orderBy('service_type.name', 'ASC')
            ->paginate(5);
        }else if($mode=="status"){
            $category=DB::table('status')
            ->select('status.*')
            ->where('status.vendor_id','=',Session::get('logged_vendor_id'))
            ->orderBy('status.display_order', 'ASC')
            ->paginate(5);
        }


        return view('vendors.vendor_category_add',compact('mode','category'));

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
        if(($mode=="category")  || ($mode=="type") || ($mode=="service_type"))
        {
            $validator = Validator::make($request->all(), [
                'cat_name' => 'required|string|max:50',

            ], [
                'cat_name.required' => 'A name is required'

              ]);
        }elseif($mode=="status")
        {
            $validator = Validator::make($request->all(), [
                'cat_name' => 'required|string|max:50',
                'notification' => 'required|string|max:50',
                'displayorder' => 'required|string|max:50',
            ], [
                'cat_name.required' => 'A name is required',
                'notification.required' => 'Notification is required',
                'displayorder.required' => 'Display Order is required'

              ]);

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
            $vcat= new ServiceType();
            $vcat->name               =$request['cat_name'];
            $saved=$vcat->save();
            if ($saved) {
                $savestatus++;
            }
        }elseif($mode=="status") {
            $vcat= new Status();
            $vcat->name                        =$request['cat_name'];
            $vcat->notification                =$request['notification'];
            $vcat->display_order               =$request['displayorder'];
            $vcat->vendor_id               =Session::get('logged_vendor_id');
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
            $vendor=DB::table('service_type')
            ->where('service_type.id','=',$id)
            ->select('service_type.*')
            ->get();
        }elseif($mode=="status"){
            $vendor=DB::table('status')
            ->select('status.*')
            ->where('status.id','=',$id)
            ->where('status.vendor_id','=',Session::get('logged_vendor_id'))
            ->orderBy('status.display_order', 'ASC')
            ->paginate(5);
        }


        //dd(DB::getQueryLog());
        return view('vendors.vendor_category_edit',compact('mode','vendor','id'));
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
            $data['name']              =$request['cat_name'];
            $data['status']              =$request['status'];
            $vendor = ServiceType::findOrFail($request['hidden_id']);
            $saved=$vendor->update($data);
            if ($saved) {
                $savestatus++;
            }
        }else if($mode=="status")
        {
            $data['name']              =$request['cat_name'];
            $data['notification']              =$request['notification'];
            $data['display_order']              =$request['displayorder'];
            $data['active']              =$request['status'];
            $vendor = Status::findOrFail($request['hidden_id']);
            $saved=$vendor->update($data);
            if ($saved) {
                $savestatus++;
            }
        }
        return $savestatus;
    }
}
