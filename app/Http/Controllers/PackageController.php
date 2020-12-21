<?php

namespace App\Http\Controllers;

use App\Package;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use illuminate\Pagination;
use Response;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Form;
use DB;

class PackageController extends Controller
{
     /**
     * Display packages page
     *
     * @return \Illuminate\View\View
     */
    public function packages_view(Package $model)
    {
        $package=Package::all();
        //return view('pages.packages',compact('package'));
        return view('package.packages', ['package' => $model->paginate(Session::get('paginate'))]);
        //return view('snippets/salary_report_tile')->with(['staff_data' => $staff_data, 'pagination' => '']);
    }
    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_type' => 'required|string|max:50',
            'package_days' => 'required|string|max:50',
            'package_amount' => 'required|string|max:50',
        ], [
            'package_type.required' => 'A package name is required',
            'package_days.required' => 'A package days is required',
            'package_amount.required' => 'A package amount is required'
          ]);
        if($validator->fails()) {
             $errors = $validator->errors();
             return Redirect()->back()->with('errors',$errors)->withInput($request->all());

        }else {
            $this->insert_packages($request);
            return Redirect('packages')->with('status', 'Package successfully Added!');
        }

    }

    public function insert_packages(Request $request)
    {

        //`package`(`id`, `type`, `days`, `status`)
        $savestatus=0;
        $package= new Package();
        $package->type               =$request['package_type'];
        $package->days               =$request['package_days'];
        $package->amount             =$request['package_amount'];
        $saved=$package->save();
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
    public function load_package(Package $model)
    {
        $package=Package::all();
        return view('snippets/package_list')->with(['package' =>$model->paginate(Session::get('paginate'))]);
    }
    public function package_edit($id)
    {
        //DB::enableQueryLog();
        $package=Package::select('package.*')
        ->where('package.id','=',$id)
        ->get();
        //dd(DB::getQueryLog());
        return view('package.packages_edit',compact('package'));
    }
    public function update(Request $request)
    {
        $updated = $this->package_update($request);
        if($updated == '2'){
            return Redirect('packages')->with('status', 'Package  Updated successfully!');
        }else{
            return Redirect('packages')->with('status', 'Sorry!');
        }
    }
    public function package_update(Request $request)
    {
        $savestatus=1;
        $data['type']              =$request['package_type'];
        $data['days']           =$request['package_days'];
        $data['amount']       =$request['package_amount'];
        $data['status']       =$request['status'];
        $package = Package::findOrFail($request['packageid']);
            $saved=$package->update($data);
            if ($saved) {
                $savestatus++;
            }
            return $savestatus;
    }
}
