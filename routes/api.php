<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//API's
Route::post('create_vendor','VendorController@create_vendor');
Route::post('insert_products','ProductController@insert_products');
Route::post('add_service','ServiceController@add_service');
Route::post('add_customer','CustomerController@add_customer');
Route::post('add_status','StatusController@add_status');
Route::post('insert_packages','PackageController@insert_packages');
Route::post('insert_jobcard','JobcardController@insert_jobcard');
Route::post('register','UserController@register_user');

Route::post('product_list', 'CommonController@getProductList');
Route::post('shorcode_generate', 'VendorController@shorcode_generate');
Route::post('webname_generate', 'VendorController@webname_generate');
Route::post('service_list', 'JobcardController@getServiceList');
