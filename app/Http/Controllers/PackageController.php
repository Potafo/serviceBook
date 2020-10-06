<?php

namespace App\Http\Controllers;

use App\Package;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use illuminate\Pagination;
use Response;

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
        return view('pages.packages', ['package' => $model->paginate(5)]);
        //return view('snippets/salary_report_tile')->with(['staff_data' => $staff_data, 'pagination' => '']);
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
        return view('snippets/package_list')->with(['package' =>$model->paginate(5)]);
    }
}
