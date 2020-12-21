<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Configuration;
use DB;
use Illuminate\Support\Facades\Validator;
use Session;
use Response;
use App\VendorConfiguration;
use App\MainConfiguration;

class ConfigurationController extends Controller
{
    public function vendorconfig_view(Request $request)
    {
        $services='';
       // $users = User::all();
       $configurations=DB::table('configuration')
       ->select('configuration.*')
       ->where('configuration.type','=','3')
       ->where('configuration.status','=','Y')
       ->where('configuration.page_view','=','Y')
       ->get();

        return view('configuration.vendor_configuration', compact('configurations'));
    }
    public function config_add(Request $request)
    {
        return view('configuration.config_add');
    }

    public function validate_jobcard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'config_name' => 'required|string|max:50',
            //'config_value' => 'required|string|max:50',
            'config_type' => 'required|string|max:50',
        ], [
            'config_name.required' => 'Name is required',
            //'config_value.required' => 'Value is required',
            'config_type.required' => 'Type is required'

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
           $this->insert_configuration($request);
            return Redirect('config_view')->with('status', 'Configuration Successfully Added!');
        }
    }
    public function insert_configuration(Request $request)
    {
       //config_view  config_status config_type config_value config_name
       //`id`, `type`, `config_name`, `value`, `status`, `page_view`, `

       //update to configuration and vendor config table

        $savestatus=0;
        $config= new Configuration();
        $config->type           =$request['config_type'];
        $config->config_name    =$request['config_name'];
        //$config->value          =$request['config_value'];
        $config->status         =$request['config_status'];
        $config->page_view      =$request['config_view'];
        $config->input_type     =$request['config_input'];
        $saved=$config->save();

        if($request['config_type']=="3"){
            $fieldname = strtolower(str_replace(" ", "_", $request['config_name']));
            $sql = "ALTER TABLE  `vendor_configuration` ADD  $fieldname varchar(100) default 'N';";
            DB::select($sql);
        }
        if($request['config_type']=="1" || $request['config_type']=="2"){
            $fieldname = strtolower(str_replace(" ", "_", $request['config_name']));
            //`type`, `name`, `value`, `config_id`
            $mainconfig= new MainConfiguration();
            $mainconfig->type           =$request['config_type'];
            $mainconfig->name    =$fieldname;
            $mainconfig->value    =null;
            $mainconfig->config_id    =$config->id;
            $saved=$mainconfig->save();
        }

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
    public function config_update(Request $request)
    {
        $field=$request['field'];
        $data[$field]       =$request['status'];
        $jobcard = VendorConfiguration::firstOrFail()->where('vendor_id', Session::get('logged_vendor_id'));
        $saved=$jobcard->update($data);
        Session::put($field, $request['status']);
        return response()->json(['message' => 'Configuration updated successfully.']);
    }

    public function config_view(Request $request)
    {
        $services='';
       // $users = User::all();
      // DB::enableQueryLog();

       $configurations= Configuration::select('ct.name as typename','configuration.*')
       ->join('configuration_type as ct','ct.id','=','configuration.type')->paginate(Session::get('paginate'));
       //dd(DB::getQueryLog());
        return view('configuration.config_view', compact('configurations'));
    }

    public function mainconfig_view(Request $request)
    {
        $services='';

      // DB::enableQueryLog();
       $configurations= MainConfiguration::select('main_configuration.*','cf.input_type')
       ->join('configuration as cf','cf.id','=','main_configuration.config_id')->get();
       //dd(DB::getQueryLog());
        return view('configuration.main_configuration', compact('configurations'));
    }
    public function config_main_update(Request $request)
    {
        $field=$request['field'];
        $data['value']       =$request['textvalue'];
        $config = MainConfiguration::findOrFail($request['id']);
        $saved=$config->update($data);
        $fieldname = strtolower(str_replace(" ", "_", $field));
        Session::put($fieldname, $request['textvalue']);
        return response()->json(['message' => 'Configuration updated successfully.']);
    }

}
