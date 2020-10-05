<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vendor;
use App\Package;
use App\RenewalList;
use App\VendorType;
use Response;
use DB;
use DateTime;
use DateTimeZone;

class VendorController extends Controller
{
     /**
     * Display packages page
     *
     * @return \Illuminate\View\View
     */
    public function vendors_view(Vendor $model)
    {
        //$vendor=Vendor::all();
       // DB::enableQueryLog();
        $vendor=Vendor::select('vendor.*','package.*','vendor_category.*','vendor.id as vid','vendor.name as vname','vendor_category.name as vcategory')
        ->rightjoin('package', 'package.id', '=', 'vendor.current_package')
        ->rightjoin('vendor_category', 'vendor_category.id', '=', 'vendor.category')
        ->orderBy('vendor.name', 'ASC')
        ->paginate(5);
        //dd(DB::getQueryLog());
        return view('vendors.vendors',compact('vendor'));
        //return view('pages.vendors', ['vendor' => $model->paginate(5)]);

    }
    public function create_vendor(Request $request)
    {
        //`vendor`(`id`, `name`, `address`, `location_lat`, `location_long`, `location_maplink`, `location_embed`,
        //`description`, `website`, `mail_id`, `image`, `contact_number`, `refferal_by`, `joined_on`, `first_package`,
        //`last_renewal_date`, `current_package`, `created_at`, `modified_at`)

        $timezone = 'ASIA/KOLKATA';
        $date = new DateTime('now', new DateTimeZone($timezone));
        $datetime = $date->format('Y-m-d H:i:s');
        $savestatus=0;
        $vendor= new Vendor();
        if($request['name']!="")
        {
            $vendor->name               =$request['name'];
            $vendor->address            =$request['address'];
            $vendor->location_lat       =$request['latitude'];
            $vendor->location_long      =$request['longitude'];
            $vendor->location_maplink   =$request['maplink'];
            $vendor->location_embed     =$request['embed'];
            $vendor->description        =$request['description'];
            $vendor->website            =$request['website'];
            $vendor->mail_id            =$request['email'];
            $vendor->image              =$request['image'];
            $vendor->contact_number     =$request['mobile'];
            $vendor->refferal_by        =$request['refferal'];
            $vendor->joined_on           =$datetime;
            $vendor->first_package       =$request['packid'];
            $vendor->current_package     =$request['packid'];
            $vendor->category            =$request['category'];
            $vendor->type                =$request['type'];
            $vendor->digital_profile_status     =$request['dps'];


            $saved=$vendor->save();
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

    private function validate_data($request, $id = null)
    {
        return $request->validate([
            'name' => 'required|unique:name,' . $id,
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'contact_number' => 'integer',
        ]);
    }
    public function vendors_view_fulllist($id)
    {
        $vendor=Vendor::select('vendor.*','package.*')
        ->join('package', 'package.id', '=', 'vendor.current_package')
        ->where('vendor.id','=',$id)
        ->paginate(1);
        //DB::enableQueryLog();
        $renewal=RenewalList::select("renewal_list.*","package.*")
        ->join('package', 'package.id', '=', 'renewal_list.package')
        ->where("renewal_list.vendor_id","=",$id)
        ->get();

        //dd(DB::getQueryLog());
        return view('vendors.vendor_view',compact('vendor','renewal'));
    }
    public function vendors_edit($id)
    {
        $vendor=Vendor::select('vendor.*','package.*')
        ->join('package', 'package.id', '=', 'vendor.current_package')
        ->where('vendor.id','=',$id)
        ->paginate(1);
        //DB::enableQueryLog();
        $renewal=RenewalList::select("renewal_list.*","package.*")
        ->join('package', 'package.id', '=', 'renewal_list.package')
        ->where("renewal_list.vendor_id","=",$id)
        ->get();

        //dd(DB::getQueryLog());
        return view('vendors.vendor_edit',compact('vendor','renewal'));
    }
    public function vendors_add()
    {

        return view('vendors.vendor_add');
    }
    public function update(Request $request)
    {
        //auth()->user()->update($request->all());
        $this->create_vendor($request);
        if($request['name']=="")
        {
            return back()->withStatus(__('Add Name'));
        }else{
            return back()->withStatus(__('Vendor successfully updated.'));
        }


    }
}
