<?php

namespace App\Http\Controllers;
use DB;
use Session;
use App\StatusChange;
use App\Chart;
use Illuminate\Support\Carbon;
use DateTime;
use DateTimeZone;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // $status_list = DB::table('vendor')
        //     ->select('*')->get();
        //     foreach ($status_list as $value) {
        //         DB::select('UPDATE vendor set joined_on="'.$value->created_at.'" where id="'.$value->id.'"');
        //     }

        $pending=0;$alerttype="";
        if (Session::get('logged_user_type') == '3') {
            $status_list = DB::table('vendor_status')
            ->select('status.name','status.id','vendor_status.ending_status')
            ->join('status','status.id','=','vendor_status.status_id')
            ->where('vendor_status.vendor_id', '=', Session::get('logged_vendor_id'))
            ->where('vendor_status.active', '=', 'Y')
            ->get();
            // DB::enableQueryLog();
            // $status_change = DB::table('status_change')
            // ->select(DB::raw('count(distinct(jobcard_number)) as statuscount'))
            // ->where('status_change.date', '=', date('Y-m-d'))
            // ->where('status_change.change_by', '=', Session::get('logged_vendor_id'))
            // ->get();
            // dd(DB::getQueryLog());
            $i=0;
            $dashboard_list=array();
            foreach ($status_list as $value) {

                $dashboard_list[$i]['name']=$value->name;
                $dashboard_list[$i]['id']=$value->id;
                if($value->ending_status==0)
                {//DB::enableQueryLog();
                    $status_change = DB::table('status_change')
                    ->select(DB::raw('count(*) as statuscount'))
                    ->where('status_change.to_status', '=', $value->id)
                    ->where('status_change.date', '=', date('Y-m-d'))
                    ->where('status_change.change_by', '=', Session::get('logged_vendor_id'))
                    ->get();
                    // $status_change = DB::table('status_change')
                    // ->select(DB::raw('max(id) as id'))
                    // ->where('status_change.to_status', '=', $value->id)
                    // ->where('status_change.date', '=', date('Y-m-d'))
                    // ->where('status_change.change_by', '=', Session::get('logged_vendor_id'))
                    // ->get();
                    //dd(DB::getQueryLog());
                    $dashboard_list[$i]['count']=$status_change[0]->statuscount;

                }elseif($value->ending_status==1)
                {
                    $status_change = DB::table('status_change_history')
                    ->select(DB::raw('count(*) as statuscount'))
                    ->where('status_change_history.to_status', '=', $value->id)
                    ->where('status_change_history.date', '=', date('Y-m-d'))
                    ->where('status_change_history.change_by', '=', Session::get('logged_vendor_id'))
                    ->get();
                    $dashboard_list[$i]['count']=$status_change[0]->statuscount;
                }
                $i++;

            }
            $chart=array();
            $chart_test=array();
            foreach($dashboard_list as $key=>$value){
                $chart_test[$value['name']]=$value['count'];
            }

            for ($i=0; $i<=count($chart_test); $i++) {
            $colours[] = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
            }
            // Prepare the data for returning with the view
            $chart = new Chart;
            $chart->labels = (array_keys($chart_test));
            $chart->dataset = (array_values($chart_test));
            $chart->colours = $colours;
            $chart->label = "Status Count";

            $title="Job Card Details";


            // vendor days expired or not

            $timezone = 'ASIA/KOLKATA';
            $date = new DateTime('now', new DateTimeZone($timezone));
             //$datetime = $date->format('Y-m-d H:i:s');
             $vendor=DB::table('vendor')
             ->join('package', 'package.id', '=', 'vendor.current_package')
             ->join('vendor_category', 'vendor_category.id', '=', 'vendor.category')
             ->join('vendor_type', 'vendor_type.id', '=', 'vendor.type')
             ->join('users','users.id','=','vendor.user_id')
             ->select('vendor.*','vendor.id as vid','vendor.last_renewal_date','vendor.name as vname','package.days','package.type as pname','vendor.joined_on','vendor.contact_number','vendor_category.name as vcategory','vendor_type.name as vtype')
             ->orderBy('vendor.name', 'ASC')
             ->where('vendor.id','=',Session::get('logged_vendor_id'))
             ->where('vendor.deleted_at','=',null)
             ->get();

            $package_days_count=$vendor[0]->days;
            if($vendor[0]->last_renewal_date == null)
                $joined_date=date("Y-m-d",strtotime($vendor[0]->joined_on));
            else {
                $joined_date=date("Y-m-d",strtotime($vendor[0]->last_renewal_date));
                }
            // $joined_date=date("Y-m-d",strtotime($vendor[0]->joined_on));
             $current_date=date("Y-m-d");
             $diff=(new DateTime($joined_date))->diff(new DateTime($current_date))->days;
             $pending=intval($package_days_count) - intval($diff);
            if($pending<=0)
            {
                $pending= "Expired";
                $alerttype="red";
                Session::put("vendor_expired", "Y");
            }elseif($pending==1)
            {
                $pending=$pending ." Day more";
                $alerttype="green";
                Session::put("vendor_expired", "N");
            }
            else {
                $pending=$pending ." Days more";
                $alerttype="green";
                Session::put("vendor_expired", "N");
            }



        } else if (Session::get('logged_user_type') == '1') {
            $now = new \DateTime('now');
            $month = $now->format('m');
            $year = $now->format('Y');
            $day = $now->format('d');
            Session::put("vendor_expired", "N");
            $today = DB::table('vendor')
            ->select(DB::raw('count(id) as `data`'))
            ->whereDay('created_at','=',$day)
            ->where('deleted_at','=',null)
            ->get();
            if(count($today)>0)
            {
                $dashboard_list[0]['name']="Today";
                $dashboard_list[0]['label']="Today";
                $dashboard_list[0]['count']=$today[0]->data;
            }else{
                $dashboard_list[0]['name']="Today";
                $dashboard_list[0]['label']="Today";
                $dashboard_list[0]['count']=0;
            }


            $last_30days = DB::table('vendor')
            ->select(DB::raw('count(id) as `data`'))
            ->where('deleted_at','=',null)
            ->where('created_at','>=',Carbon::now()->subdays(30))->get(['name','created_at']);
            if(count($last_30days)>0)
            {
                $dashboard_list[1]['name']="Last 30 days";
                $dashboard_list[1]['label']="Last 30 days";
                $dashboard_list[1]['count']=$last_30days[0]->data;
            }else{
                $dashboard_list[1]['name']="Last 30 days";
                $dashboard_list[1]['label']="Last 30 days";
                $dashboard_list[1]['count']=0;
            }


            $this_month = DB::table('vendor')
            ->select(DB::raw('count(id) as `data`') ,  DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
            ->whereMonth('created_at', '=', $month)
            ->where('deleted_at','=',null)
            ->groupby('month')
            ->get();
            if(count($this_month)>0)
            {
                $dashboard_list[2]['name']="This Month";
                $dashboard_list[2]['label']="This Month";
                $dashboard_list[2]['count']=$this_month[0]->data;
            }else{
                $dashboard_list[2]['name']="This Month";
                $dashboard_list[2]['label']="This Month";
                $dashboard_list[2]['count']=0;
            }


            $this_year = DB::table('vendor')
            ->select(DB::raw('count(id) as `data`'), DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),  DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
            ->whereYear('created_at', '=', $year)
            ->where('deleted_at','=',null)
            ->groupby('year')
            ->get();
            if(count($this_year)>0)
            {
                $dashboard_list[3]['name']="This Year";
                $dashboard_list[3]['label']="This Year";
                $dashboard_list[3]['count']=$this_year[0]->data;
            }else{
                $dashboard_list[3]['name']="This Year";
                $dashboard_list[3]['label']="This Year";
                $dashboard_list[3]['count']=0;
            }



            $last_year = DB::table('vendor')
            ->select(DB::raw('count(id) as `data`'), DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),  DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
            ->whereYear('created_at', '=', $year-1)
            ->where('deleted_at','=',null)
            ->groupby('year')
            ->get();
            if(count($last_year)>0)
            {
                $dashboard_list[4]['name']="Last Year";
                $dashboard_list[4]['label']="Last Year";
                $dashboard_list[4]['count']=$last_year[0]->data;
            }else{
                $dashboard_list[4]['name']="Last Year";
                $dashboard_list[4]['label']="Last Year";
                $dashboard_list[4]['count']=0;
            }


            //DB::enableQueryLog();
            $lastDay = date('t',strtotime(date('d/m/Y')));

            $this_month_chart = DB::table('vendor')
            ->select(DB::raw('count(id) as `data`') ,  DB::raw('DAY(created_at) day, MONTH(created_at) month'))
            ->whereMonth('created_at', '=', $month)
            ->where('deleted_at','=',null)
            ->groupby('day')
            ->orderby('day','ASC')
            ->get();
            $j=0;
            if(count($this_month_chart)>0)
            {
                foreach($this_month_chart as $key=>$value){
                    $thismonth_list[$j]['count']=$value->data;
                    $thismonth_list[$j]['name']=$value->day;
                    $thismonth_list[$j]['label']=$value->day;
                    $j++;
                    //$chart_test[$value['name']]=$value['count'];
                }
            }else{
                     $thismonth_list[$j]['count']="";
                    $thismonth_list[$j]['name']="";
                    $thismonth_list[$j]['label']="";
            }

            $chart=array();
            $chart_test=array();
            //for($i=0;$i++;$i<=$lastDay)
           // {
                foreach($thismonth_list as $key=>$value){
                    $chart_test[$value['name']]=$value['count'];
                }
           // }//dd($thismonth_list);
            for ($i=0; $i<=count($chart_test); $i++) {
                $colours[] = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
                }
                // Prepare the data for returning with the view
                $chart = new Chart;
                $chart->labels = (array_keys($chart_test));
                $chart->dataset = (array_values($chart_test));
                $chart->colours = $colours;
                $chart->label = "Vendor Count";

            //dd(DB::getQueryLog());
            $title="Vendor Details";

        }



        return view('dashboard',compact('dashboard_list','chart','title','pending','alerttype'));
    }


}
