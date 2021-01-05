<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use App\Jobcard;
use Maatwebsite\Excel\Concerns\Exportable;
use Session;
use Illuminate\Http\Request;
class JobcardReportExport implements FromView
{
    use Exportable;
    protected $request;


    public function __construct($request = null)
    {
        $this->request = $request;

    }

    public function view(): View
    {
    //    $selectedDevice = Session::get('selectedDevice');
    //   $SerialNumber = request()->input('SerialNumber');
    //   $DateTime = request()->input('DateTime');
    //  $Data = DB::table('tableName')->where('SerialNumber',$SerialNumber)
    //   ->where('DateTime','<=',$DateTime)->orderBy('id','desc')->take(2)->get();


    $request = $this->request;

    $status_list = DB::table('vendor_status')
    ->where('vendor_status.vendor_id', '=', Session::get('logged_vendor_id'))
    ->where('vendor_status.active', '=', 'Y')
    ->where('vendor_status.ending_status', '=', '0')
    ->pluck('vendor_status.status_id')->toArray();
     // DB::enableQueryLog();

        $rows1 = Jobcard::leftjoin('customers', 'customers.id', '=', 'job_card.customer_id')
        ->leftjoin('jobcard_bills', 'jobcard_bills.jobcard_number', '=', 'job_card.jobcard_number')
        ->select('customers.name as custname', 'customers.id as custid', 'customers.contact_number as custmobile',  'job_card.jobcard_number', 'job_card.date as jobcard_date', 'job_card.id','jobcard_bills.received_amount','jobcard_bills.bill_amount','jobcard_bills.discount_amount','jobcard_bills.tax_amount')
        ->orderBy('job_card.created_at', 'DESC')
        ->where('job_card.jobcard_number','not like','Temp-%')
        //->where('job_card.current_status',\DB::raw($status_list),">",\DB::raw("'0'"))
        ->whereNotIn('job_card.current_status',$status_list);


    $jobcard = array();
    if (Session::get('logged_user_type') == '3') {
        $vendor_id = Session::get('logged_vendor_id');
        $jobcard = $rows1->where('job_card.vendor_id', '=', $vendor_id);
    } else if (Session::get('logged_user_type') == '1') {
    }
    $filter_details=array();
    $filter_details['filter_fromdate']="";
    $filter_details['filter_todate']="";
    if (!empty($request['filter_fromdate']) && !empty($request['filter_todate'])) {
        $dfrom=date("Y-m-d",strtotime($request['filter_fromdate']));
        $dto=date("Y-m-d",strtotime($request['filter_todate']));
        $rows1->whereBetween('job_card.date', [$dfrom, $dto]);
        $filter_details['filter_fromdate']=$request['filter_fromdate'];
        $filter_details['filter_todate']=$request['filter_todate'];
    }
    if (!empty($request['filter_fromdate']) && empty($request['filter_todate'])) {
        $dfrom=date("Y-m-d",strtotime($request['filter_fromdate']));
        $dto=date("Y-m-d");
        $rows1->whereBetween('job_card.date', [$dfrom, $dto]);
        $filter_details['filter_fromdate']=$request['filter_fromdate'];
        $filter_details['filter_todate']="";
    }
    if (empty($request['filter_fromdate']) && !empty($request['filter_todate'])) {
        $dfrom=date("Y-m-d",strtotime($request['filter_todate']));
        $dto=date("Y-m-d",strtotime($request['filter_todate']));
        $rows1->whereBetween('job_card.date', [$dfrom, $dto]);
        $filter_details['filter_fromdate']="";
        $filter_details['filter_todate']=$request['filter_todate'];
    }


    $filter_details['filter_globalsearch']='';
    if (!empty($request['filter_globalsearch'])) {
        $searchQuery =  $request['filter_globalsearch'];

            $jobcard =$rows1->where(function ($q) use ($searchQuery) {
                $q->Where('customers.name', 'LIKE', '%' .$searchQuery. '%')
                ->orWhere('customers.contact_number', 'LIKE', '%' . $searchQuery. '%')
                ->orWhere('job_card.jobcard_number', 'LIKE',  '%' .$searchQuery. '%')
                ->orWhere('job_card.date', 'LIKE', '%' . $searchQuery. '%')
                ->orWhere('jobcard_bills.received_amount', 'LIKE',  '%' .$searchQuery. '%');
            });

        $filter_details['filter_globalsearch']=$request['filter_globalsearch'];
    }

    $jobcard = $rows1->get();

    //return $jobcard;


        $params = ['page'=>'page','jobcard'=>$jobcard];

        return view('snippets.jobcard_report', $params);
    }
}
