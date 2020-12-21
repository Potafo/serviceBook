<?php
namespace App\Traits;

use Illuminate\Http\Request;
use DateTime;
use DateTimeZone;
use App\Product;
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
}


?>
