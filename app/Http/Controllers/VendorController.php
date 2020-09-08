<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vendor;
use Response;

class VendorController extends Controller
{
    //
    public function create_vendor(Request $request)
    {
        //`vendor`(`id`, `name`, `address`, `location_lat`, `location_long`, `location_maplink`,
        //`location_embed`, `description`, `website`, `mail_id`, `image`, `contact_number`, `created_at`, `modified_at`)
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
}
