<?php

namespace App\Http\Controllers;

use Response;
use DB;
use Illuminate\Http\Request;
use App\VendorCategory;
use App\VendorType;
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
        }else{
            $category=DB::table('vendor_type')
            ->select('vendor_type.*')
            ->orderBy('vendor_type.name', 'ASC')
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
        }else{
            $category=DB::table('vendor_type')
            ->select('vendor_type.*')
            ->orderBy('vendor_type.name', 'ASC')
            ->paginate(5);
        }

        return view('vendors.vendor_category_add',compact('mode','category'));

    }
    public function insert(Request $request)
    {
        $mode=$request['hidden_mode'];
        if(($mode=="category")  || ($mode=="type") )
        {
            $validator = Validator::make($request->all(), [
                'cat_name' => 'required|string|max:50',

            ], [
                'cat_name.required' => 'A name is required'

              ]);
              if($validator->fails()) {
                $errors = $validator->errors();
                return Redirect()->back()->with('errors',$errors)->withInput($request->all());

                }else {
                    $this->insert_vendorcategory($request);
                    return Redirect('vendor_category/'.$request['hidden_mode'])->with('status', 'Vendor '. $mode.' Added!');
                }
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
        }else{
            $vendor=DB::table('vendor_type')
            ->where('vendor_type.id','=',$id)
            ->select('vendor_type.*')
            ->get();
        }


        //dd(DB::getQueryLog());
        return view('vendors.vendor_category_edit',compact('mode','vendor','id'));
    }
    public function update(Request $request)
    {
      $updated = $this->update_sql($request);
        if($updated == '2'){
            return Redirect('vendor_category/'.$request['hidden_mode'])->with('status', 'Vendor successfully Updated!');
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
        }
        return $savestatus;
    }
}
