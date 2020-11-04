<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Response;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;

class ProductController extends Controller
{
    public function insert_products(Request $request)
    {
        //`products`(`id`, `name`, `image`, `vendor_id`, `created_at`, `modified_at`)
        $savestatus=0;
        $product= new Product();
        $product->name               =$request['productname'];
        //$product->image              =$request['image'];
        $product->vendor_id          =Session::get('logged_vendor_id');
        // Check if a profile image has been uploaded
        if ($request->has('file')) {
            // Get image file
            $image = $request->file('file');
            // Make a image name based on user name and current timestamp
            $name = Str::slug($request->input('productname')).'_'.time();
            // Define folder path
            $folder = 'uploads/vendor_products/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
            // Upload image
            //$this->uploadOne($image, $folder, 'public', $name);
            $filename=$name;
            $name = !is_null($filename) ? $filename : Str::random(25);

            $file = $image->storeAs($folder, $name.'.'.$image->getClientOriginalExtension(), 'public');

            // Set user profile image path in database to filePath
            $product->image=$filePath;
        }



        $saved=$product->save();
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
    public function products_view(Product $model)
    {
        $vendor_id=Session::get('logged_vendor_id');
        //$products=Product::all();
        $products=Product::select('products.*')
            ->where('products.vendor_id','=',$vendor_id)
            ->paginate(5);
        return view('products.products',compact('products'));
        //return view('snippets/salary_report_tile')->with(['staff_data' => $staff_data, 'pagination' => '']);
    }
    public function validate_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productname' => 'required|string|max:50',
            'file' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'productname.required' => 'Product Name is required'

          ]);
          return $validator;
    }
    public function insert(Request $request)
    {
        $validator=$this->validate_data($request);
        if($validator->fails()) {
             $errors = $validator->errors();
             return Redirect()->back()->with('errors',$errors)->withInput($request->all());
        }else {
            $this->insert_products($request);
            return Redirect('products')->with('status', 'Products Successfully Added!');
        }
    }
    public function products_edit($id)
    {

        $products=DB::table('products')
        ->select('products.*')
        ->where('products.id','=',$id)
        ->get();

        //dd(DB::getQueryLog());
        return view('products.products_edit',compact('products','id'));
    }
    public function update(Request $request)
    {
        $validator=$this->validate_data($request);
        if($validator->fails()) {
            $errors = $validator->errors();
            return Redirect()->back()->with('errors',$errors)->withInput($request->all());

       }else {
         $updated = $this->update_sql($request);
           return Redirect('products')->with('status', 'Products Updated successfully!');
       }

    }
    public function update_sql(Request $request)
    {
        //$vendor= new Vendor();
        $savestatus=1;

           //$data['name']              =$request['jobcard_id'];
           $data['name']           =$request['productname'];
        //    / $data['product_id']           =$request['productname'];
            // Check if a profile image has been uploaded
            if ($request->has('file')) {
                // Get image file
                $image = $request->file('file');
                // Make a image name based on user name and current timestamp
                $name = Str::slug($request->input('productname')).'_'.time();
                // Define folder path
                $folder = 'uploads/vendor_logo/';
                // Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
                // Upload image
                //$this->uploadOne($image, $folder, 'public', $name);
                $filename=$name;
                $name = !is_null($filename) ? $filename : Str::random(25);

                $file = $image->storeAs($folder, $name.'.'.$image->getClientOriginalExtension(), 'public');

                // Set user profile image path in database to filePath
                $data['image']=$filePath;
            }
            $vendor = Product::findOrFail($request['products_id']);
            $saved=$vendor->update($data);
            if ($saved) {
                $savestatus++;
            }

        return $savestatus;
    }

}
