<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
		Route::get('icons', ['as' => 'pages.icons', 'uses' => 'PageController@icons']);
		Route::get('maps', ['as' => 'pages.maps', 'uses' => 'PageController@maps']);
		Route::get('notifications', ['as' => 'pages.notifications', 'uses' => 'PageController@notifications']);
		Route::get('rtl', ['as' => 'pages.rtl', 'uses' => 'PageController@rtl']);
		Route::get('tables', ['as' => 'pages.tables', 'uses' => 'PageController@tables']);
		Route::get('typography', ['as' => 'pages.typography', 'uses' => 'PageController@typography']);
        Route::get('upgrade', ['as' => 'pages.upgrade', 'uses' => 'PageController@upgrade']);



});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
    //vendor
    Route::get('vendors', ['as' => 'vendors.vendors', 'uses' => 'VendorController@vendors_view']);
    Route::get('vendor_view/{id}', ['as' => 'vendors.vendor_view', 'uses' => 'VendorController@vendors_view_fulllist']);
    Route::get('vendor_edit/{id}', ['as' => 'vendors.vendor_edit', 'uses' => 'VendorController@vendors_edit']);
    Route::get('vendor_add', ['as' => 'vendors.vendor_add', 'uses' => 'VendorController@vendors_add']);

    Route::put('vendors_i', ['as' => 'vendors.insert', 'uses' => 'VendorController@insert']);
    Route::put('vendors_u', ['as' => 'vendors.update', 'uses' => 'VendorController@update']);
    Route::put('vendors_r', ['as' => 'vendors.renew', 'uses' => 'VendorController@renew']);

    Route::get('vendor_category/{mode}', ['as' => 'vendors.vendor_category', 'uses' => 'VendorCategoryController@vendorcategory_view']);
    Route::get('vendorcategory_add/{mode}', ['as' => 'vendors.vendor_category_add', 'uses' => 'VendorCategoryController@vendorcategory_add']);
    Route::put('vendor_category_insert', ['as' => 'vendorcategory.insert', 'uses' => 'VendorCategoryController@insert']);
    Route::get('vendor_category_edit/{mode}/{id}', ['as' => 'vendors.vendor_category_edit', 'uses' => 'VendorCategoryController@vendor_category_edit']);
    Route::put('vendor_category_update', ['as' => 'vendorcategory.update', 'uses' => 'VendorCategoryController@update']);
    //package
    Route::put('package_insert', ['as' => 'package.insert', 'uses' => 'PackageController@insert']);
    Route::get('packages', ['as' => 'package.packages', 'uses' => 'PackageController@packages_view']);
    Route::get('load_package', 'PackageController@load_package')->name('load_package');
    Route::get('package_add', function() {
        return view('package.packages_add');
    });
    Route::get('package_edit/{id}', ['as' => 'package.package_edit', 'uses' => 'PackageController@package_edit']);
    Route::put('package_update', ['as' => 'package.update', 'uses' => 'PackageController@update']);

    //jobcard
    Route::get('jobcard', ['as' => 'jobcard.jobcard', 'uses' => 'JobcardController@jobcard_view']);
    Route::get('jobcard_add', ['as' => 'jobcard.jobcard_add', 'uses' => 'JobcardController@jobcard_add']);
    Route::put('jobcard_insert', ['as' => 'jobcard.insert', 'uses' => 'JobcardController@insert']);
    Route::get('jobcard_edit/{id}', ['as' => 'jobcard.jobcard_edit', 'uses' => 'JobcardController@jobcard_edit']);
    Route::put('jobcard_update', ['as' => 'jobcard.update', 'uses' => 'JobcardController@update']);

    //products
    Route::get('products', ['as' => 'products.products', 'uses' => 'ProductController@products_view']);
    Route::get('products_add', function() {
        return view('products.products_add');
    });
    Route::put('products_insert', ['as' => 'products.insert', 'uses' => 'ProductController@insert']);
    Route::get('products_edit/{id}', ['as' => 'products.products_edit', 'uses' => 'ProductController@products_edit']);
    Route::put('products_update', ['as' => 'products.update', 'uses' => 'ProductController@update']);
});

