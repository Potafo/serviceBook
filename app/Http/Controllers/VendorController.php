<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vendor;
use App\Package;
use App\RenewalList;
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
        $vendor=Vendor::select('vendor.*','package.*','vendor.id as vid')
        ->join('package', 'package.id', '=', 'vendor.current_package')
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
        $vendor->name               =$request['name'];
        $vendor->address            =$request['address'];
        $vendor->location_lat       =$request['location_lat'];
        $vendor->location_long      =$request['location_long'];
        $vendor->location_maplink   =$request['location_maplink'];
        $vendor->location_embed     =$request['location_embed'];
        $vendor->description        =$request['description'];
        $vendor->website            =$request['website'];
        $vendor->mail_id            =$request['mail_id'];
        $vendor->image              =$request['image'];
        $vendor->contact_number     =$request['contact_number'];
        $vendor->refferal_by        =$request['refferal_by'];
        $vendor->joined_on           =$datetime;
        $vendor->first_package       =$request['packid'];
        $vendor->current_package     =$request['packid'];

        $saved=$vendor->save();
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
}
