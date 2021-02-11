<?php
namespace App\Traits;

use Illuminate\Http\Request;
use DateTime;
use DateTimeZone;
use App\Product;
use App\VendorStatus;
use App\Http\Requests\UserRequest;
use Session;

trait ListQueryById
{
    public function product_list_query($vendorid)
    {
         $productlist=Product::select('id','name')
         ->where('vendor_id','=',$vendorid)
         ->get();
        return $productlist;
    }
    public function sendemail_sms($vendorid,$status)
    {
         $send_list=VendorStatus::select('send_sms','send_email')
         ->where('vendor_id','=',$vendorid)
         ->where('status_id','=',$status)
         ->get();
        return $send_list;
    }
}


?>
