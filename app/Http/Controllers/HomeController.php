<?php

namespace App\Http\Controllers;
use DB;
use Session;
use App\StatusChange;
use App\Chart;
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
        if (Session::get('logged_user_type') == '3') {
            $status_list = DB::table('vendor_status')
            ->select('status.name','status.id','vendor_status.ending_status')
            ->join('status','status.id','=','vendor_status.status_id')
            ->where('vendor_status.vendor_id', '=', Session::get('logged_vendor_id'))
            ->where('vendor_status.active', '=', 'Y')
            ->get();
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
                    $dashboard_list[$i]['count']=$status_change[0]->statuscount;
                    //dd(DB::getQueryLog());
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

        } else if (Session::get('logged_user_type') == '1') {
           // DB::enableQueryLog();
            $status_list = DB::table('vendor_status')
            ->select('status.name','status.id','vendor_status.ending_status')
            ->join('status','status.id','=','vendor_status.status_id')
            //->where('vendor_status.vendor_id', '=', Session::get('logged_vendor_id'))
            ->where('vendor_status.active', '=', 'Y')
            ->groupBy('status.id')
            ->get();
            //dd(DB::getQueryLog());
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
                    ->get();
                    $dashboard_list[$i]['count']=$status_change[0]->statuscount;
                    //dd(DB::getQueryLog());
                }elseif($value->ending_status==1)
                {
                    $status_change = DB::table('status_change_history')
                    ->select(DB::raw('count(*) as statuscount'))
                    ->where('status_change_history.to_status', '=', $value->id)
                    ->where('status_change_history.date', '=', date('Y-m-d'))
                    ->get();
                    $dashboard_list[$i]['count']=$status_change[0]->statuscount;
                }
                $i++;

            }
        }
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
     //       return view('charts.index', compact('chart'));
    //dd($dashboard_list);

        return view('dashboard',compact('dashboard_list','chart'));
    }
}
