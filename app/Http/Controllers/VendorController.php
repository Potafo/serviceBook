<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vendor;
use App\Package;
use App\RenewalList;
use App\VendorType;
use App\UserLogin;
use Response;
use DB;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Rules\PhoneNumber;
//use App\Http\Controllers\Auth;
use App\Http\Controllers\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
class VendorController extends Controller
{
     /**
     * Display packages page
     *
     * @return \Illuminate\View\View
     */
    public function vendor_list_query()
    {
        $logged_user_id = Auth::id();
        $rows1=DB::table('vendor')
        ->join('package', 'package.id', '=', 'vendor.current_package')
        ->join('vendor_category', 'vendor_category.id', '=', 'vendor.category')
        ->join('vendor_type', 'vendor_type.id', '=', 'vendor.type')
        ->select('vendor.id as vid','vendor.name as vname','package.days','package.type as pname','vendor.joined_on','vendor.contact_number','vendor_category.name as vcategory','vendor_type.name as vtype')
        ->orderBy('vendor.name', 'ASC');
        if($logged_user_id=="1")
        {
            $vendor=$rows1->paginate(5);
        }else{
           $vendor= $rows1->where('vendor.user_id','=',$logged_user_id)
            ->paginate(5);
        }
        return $vendor;
    }
    public function vendors_view()
    {
        //$vendor=Vendor::all();
       // DB::enableQueryLog();
       // dd(DB::getQueryLog());
        $vendor=$this->vendor_list_query();
      // $vendor=Vendor::table("SELECT *,vendor.id as vid, package.type as pname,vendor_category.name as vcategory,vendor_type.name as vtype FROM `vendor`  join package   ON vendor.current_package=package.id join vendor_type on vendor.type=vendor_type.id JOIN vendor_category on vendor_category.id=vendor.category")->get()->paginate(2);
        return view('vendors.vendors',compact('vendor'));
       // return view('vendors.vendors', ['vendor' => $model->paginate(2)]);

    }
    public function valid_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'latitude' => 'required|string|max:50',
            'longitude' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'mobile' => ['required', new PhoneNumber],
            'shortkey' => 'required|string|max:6',
            'file' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'A Vendor name is required',
            'latitude.required' => 'Latitude is required',
            'longitude.required' => 'Longitude is required',
            'email.required' => 'Email is required',
            'mobile.required' => 'Mobile is required',
            'shortkey.required' => 'Shortkey is required'
          ]);
          return $validator;
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
            $logged_user_id = Auth::id();
            $vendor->name               =$request['name'];
            $vendor->user_id            =$logged_user_id;
            $vendor->address            =$request['address'];
            $vendor->location_lat       =$request['latitude'];
            $vendor->location_long      =$request['longitude'];
            $vendor->location_maplink   =$request['maplink'];
            $vendor->location_embed     =$request['embed'];
            $vendor->description        =$request['description'];
            $vendor->website            =$request['website'];
            $vendor->mail_id            =$request['email'];
            //$vendor->image              =$request['image'];
            $vendor->contact_number     =$request['mobile'];
            $vendor->refferal_by        =$request['refferal'];
            $vendor->joined_on           =$datetime;
            $vendor->first_package       =$request['packid'];
            $vendor->current_package     =$request['packid'];
            $vendor->category            =$request['category'];
            $vendor->type                =$request['type'];
            $vendor->digital_profile_status     =$request['dps'];
            $vendor->shortkey       =$request['shortkey'];



            // Check if a profile image has been uploaded
            if ($request->has('file')) {
                // Get image file
                $image = $request->file('file');
                // Make a image name based on user name and current timestamp
                $name = Str::slug($request->input('name')).'_'.time();
                // Define folder path
                $folder = 'uploads/vendor_logo/';
                // Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
                // Upload image
                //$this->uploadOne($image, $folder, 'public', $name);
                $filename=$name;
                $name = !is_null($filename) ? $filename : Str::random(25);

                $file = $image->storeAs($folder, $name.'.'.$image->getClientOriginalExtension(), 'public');

                // Set user profile image path in database to filePath
                $vendor->image=$filePath;
            }


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


    public function vendors_view_fulllist($id)
    {
        $vendor=Vendor::select('vendor.*','package.*')
        ->join('package', 'package.id', '=', 'vendor.current_package')
        ->where('vendor.id','=',$id)
        ->get();
        //DB::enableQueryLog();
        $renewal=RenewalList::select("renewal_list.*","package.*")
        ->join('package', 'package.id', '=', 'renewal_list.package')
        ->where("renewal_list.vendor_id","=",$id)
        ->paginate(5);
        //SELECT MAX(`login_time`) FROM `user_logindetails` WHERE userid='2'
        $userlogin=UserLogin::select(DB::raw('MAX(user_logindetails.login_time) AS logintime'))
        ->where("user_logindetails.userid","=",$id)
        ->get();
        //dd(DB::getQueryLog());
        return view('vendors.vendor_view',compact('vendor','renewal','id','userlogin'));
    }
    public function vendors_edit($id)
    {
        // $vendor=Vendor::select('vendor.*','package.*')
        // ->join('package', 'package.id', '=', 'vendor.current_package')
        // ->where('vendor.id','=',$id)
        // ->paginate(1);
        // //DB::enableQueryLog();
        // $renewal=RenewalList::select("renewal_list.*","package.*")
        // ->join('package', 'package.id', '=', 'renewal_list.package')
        // ->where("renewal_list.vendor_id","=",$id)
        // ->get();
        $vendor=DB::table('vendor')
        ->join('package', 'package.id', '=', 'vendor.current_package')
        ->join('vendor_category', 'vendor_category.id', '=', 'vendor.category')
        ->join('vendor_type', 'vendor_type.id', '=', 'vendor.type')
        ->select('vendor.*','vendor.id as vid','vendor.name as vname','package.days','package.type as pname','vendor.joined_on','vendor.contact_number','vendor_category.name as vcategory','vendor_type.name as vtype')
        ->orderBy('vendor.name', 'ASC')
        ->where('vendor.id','=',$id)
        ->get();

        //dd(DB::getQueryLog());
        return view('vendors.vendor_edit',compact('vendor'));
    }
    public function vendors_add()
    {

        return view('vendors.vendor_add');
    }

    public function insert(Request $request)
    {
// /regex:/(0)[0-9]/|not_regex:/[a-z]/
        $validator=$this->valid_data($request);
          //ALTER TABLE `vendor` ADD `shortkey` VARCHAR(6) NULL AFTER `name`;
        if($validator->fails()) {
             $errors = $validator->errors();
             return Redirect()->back()->with('errors',$errors)->withInput($request->all());

        }else {
            $this->create_vendor($request);
            return Redirect('vendors')->with('status', 'Vendor successfully Added!');
        }




        // $this->create_vendor($request);
        // if($request['name']=="")
        // {
        //     return back()->withStatus(__('Add Name'));
        // }else{
        //     return Redirect('vendors')->with('status', 'Vendor successfully Added!');
        // }


    }
    public function update_sql(Request $request)
    {
        //$vendor= new Vendor();
        $savestatus=1;

            $data['name']              =$request['name'];
            $data['address']           =$request['address'];
            $data['location_lat']       =$request['latitude'];
            $data['location_long']      =$request['longitude'];
            $data['location_maplink']   =$request['maplink'];
            $data['location_embed']     =$request['embed'];
            $data['description']        =$request['description'];
            $data['website']            =$request['website'];
            $data['mail_id']            =$request['email'];
            $data['image']              =$request['image'];
            $data['contact_number']     =$request['mobile'];
            $data['refferal_by']        =$request['refferal'];
            $data['shortkey']           =$request['shortkey'];

            //$data['first_package']       =$request['packid'];
            //$data['current_package']     =$request['packid'];
            $data['category']            =$request['category'];
            $data['type']                =$request['type'];
            $data['digital_profile_status']     =$request['dps'];



            // Check if a profile image has been uploaded
            if ($request->has('file')) {
                // Get image file
                $image = $request->file('file');
                // Make a image name based on user name and current timestamp
                $name = Str::slug($request->input('name')).'_'.time();
                // Define folder path
                $folder = 'uploads/vendor_logo/';
                // Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
                // Upload image
                //$this->uploadOne($image, $folder, 'public', $name);
                $filename=$name;
                $name = !is_null($filename) ? $filename : Str::random(25);

                $file = $image->storeAs($folder, $name.'.'.$image->getClientOriginalExtension(), 'public');

                // Set user profile image path in database to filePath
                $data['image']=$filePath;
            }



            $vendor = Vendor::findOrFail($request['vendorid']);
            $saved=$vendor->update($data);
            if ($saved) {
                $savestatus++;
            }

        return $savestatus;
    }
    public function update(Request $request)
    {
        $validator=$this->valid_data($request);
        if($validator->fails()) {
            $errors = $validator->errors();
            return Redirect()->back()->with('errors',$errors)->withInput($request->all());

       }else {
        $updated = $this->update_sql($request);
           return Redirect('vendors')->with('status', 'Vendor Updated successfully!');
       }




    }
    public function renewallist(Request $request)
    {
    // /`renewal_list`(`id`, `vendor_id`, `renewal_date`, `package`, `amount_paid`
        $savestatus=1;
        $renew= new RenewalList();
        $renew->vendor_id               =$request['vendor_id'];
        $renew->renewal_date               =date("Y-m-d",strtotime($request['next_renewal']));
        $renew->package               =$request['package_to_renew'];
        $renew->amount_paid               =$request['pack_amount_renew'];
        $saved=$renew->save();
        if ($saved) {
            $savestatus++;
        }

        $timezone = 'ASIA/KOLKATA';
        $date = new DateTime('now', new DateTimeZone($timezone));
        $datetime = $date->format('Y-m-d H:i:s');
        $data['current_package']     =$request['package_to_renew'];
        $data['last_renewal_date']          =$datetime;
        $vendor = Vendor::findOrFail($request['vendor_id']);
        $saved=$vendor->update($data);

        return $savestatus;

    }
    public function renew(Request $request)
    {
      $updated = $this->renewallist($request);
        if($updated == '2'){
            return back()->with('status', 'Renewed successfully');
        }else{
            return back()->with('status', 'Sorry!');
        }

    }
}
