<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Traits\ListQueryById;

class CommonController extends Controller
{
    use ListQueryById;
    public function getProductList(Request $request)
    {
        if(Session::get('logged_user_type') =='3')
        {
            $productlist=$this->product_list_query(Session::get('logged_vendor_id'));
        }else{
            $productlist=$this->product_list_query($request['vendor_id']);
        }

        $append='';
        if(count($productlist)>0)
        {
            foreach($productlist as $value)
            {
                $append.='<option value="'.$value['id'].'">'. $value['name'].'</option>';
            }
        }else{
            $append.='<option value="">No records</option>';
        }

        return $append;

    }
}
