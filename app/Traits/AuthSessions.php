<?php
namespace App\Traits;

use Illuminate\Http\Request;
use DateTime;
use DateTimeZone;
use App\User;
use App\UserLogin;
use App\Http\Requests\UserRequest;
use Session;
use App\Vendor;
use App\Configuration;
use App\MainConfiguration;
use App\VendorConfiguration;
use App\ServiceCategory;
use App\ServiceType;
use DB;
use App\AppConfiguration;

trait AuthSessions
{
    public function getsessionsAfterAuth($user)
    {
        $timezone = 'ASIA/KOLKATA';
        $date = new DateTime('now', new DateTimeZone($timezone));
        $userlogin=new UserLogin;
        $userlogin->userid =$user->id;
        $userlogin->login_time =$date;
        $userlogin->save();
        Session::put('logged_user_id', $user->id);
        Session::put('logged_user_type', $user->user_type);
        Session::put('default_package',1);
        if($user->user_type == '3') // vendor
        {
            $vendor_id=Vendor::select('id','short_code')
            ->where('vendor.user_id','=',$user->id)
            ->get();
            Session::put('logged_vendor_id', $vendor_id[0]->id);
            Session::put('logged_vendor_shortcode', $vendor_id[0]->short_code);
        }else if($user->user_type == '1') // admin
        {
            Session::put('logged_vendor_id', '');
        }
        $configurations=Configuration::select('configuration.*')->get();
        foreach($configurations as $key=>$value)
            {
                $fieldname = strtolower(str_replace(" ", "_", $value->config_name));
                Session::put($fieldname, $this->config_settings($fieldname,$value->type));
            }
            if((Session::get('logged_vendor_id') != '') )
            {
                $servicetype=ServiceType::select('service_type.*')->get();
                foreach($servicetype as $key=>$value)
                    {
                        Session::put($value->name, $value->id);
                    }
            // Session::put('Products', 5); //$this->getServicelistId('products')
            // Session::put('General', 6); //$this->getServicelistId(null)
            // Session::put('Parts', 7); //$this->getServicelistId(null)
            //DB::enableQueryLog();
            $servicetype=ServiceCategory::select('service_category.name','vendor_servicetype.status')
            ->join('vendor_servicetype','vendor_servicetype.service_category','=','service_category.id')
            ->where('vendor_servicetype.vendor_id','=',Session::get('logged_vendor_id'))
            ->groupBy('vendor_servicetype.service_category')
            ->get();
            //dd(DB::getQueryLog());
            foreach($servicetype as $key=>$value)
                {
                    Session::put($value->name."_status", $value->status);
                }
            // Session::put('Service_status', 'Y');
            // Session::put('Parts_status','Y');

            }


    }
    // public function getServicelistId($type)
    // {

    //         $servcategory=ServiceCategory::select('service_category.name as typename',\DB::raw("GROUP_CONCAT(vendor_servicetype.service_type) as sname"))
    //         ->leftjoin("vendor_servicetype",\DB::raw("FIND_IN_SET(service_category.id,vendor_servicetype.service_category) "),">",\DB::raw("'0'"))
    //         ->join('service_type','service_type.id','=','vendor_servicetype.service_type')
    //         ->where('service_type.table_connected','=',$type)
    //         ->where('vendor_servicetype.vendor_id','=',Session::get('logged_vendor_id'))
    //         ->groupBy('service_category')
    //         ->get();

    //     return $servcategory[0]->sname;
    // }
    public function config_settings($field,$type)
    {
        if($type=="3" )
        {
            //DB::enableQueryLog();
            if((Session::get('logged_vendor_id') != '') )
            {
                $configuration=VendorConfiguration::select('vendor_configuration.*')
                ->where('vendor_id','=',Session::get('logged_vendor_id'))
                ->get();
                //dd(DB::getQueryLog());
                return $configuration[0]->$field;
            }else{
                return "";
            }

        }else if($type=="1"  || $type=="2")
        {
            $configuration=MainConfiguration::select('main_configuration.*')
            ->where('name','=',$field)
            ->get();

            return $configuration[0]->value;
        }else if($type=="4")
        {
            $configuration=AppConfiguration::select('app_configuration.*')
            ->where('name','=',$field)
            ->get();

            return $configuration[0]->value;
        }

    }
}


?>
