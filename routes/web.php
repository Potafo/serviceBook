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
    Route::get('filter_by_vendorid', ['as' => 'vendorcategory.filter_by_vendorid', 'uses' => 'VendorCategoryController@filter_by_vendorid']);
    Route::post('set_vendorid', ['as' => 'set_vendorid', 'uses' => 'VendorCategoryController@set_vendorid']);
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
    Route::post('jobcard_insert', ['as' => 'jobcard.insert', 'uses' => 'JobcardController@insert']);
    Route::get('jobcard_edit/{id}', ['as' => 'jobcard.jobcard_edit', 'uses' => 'JobcardController@jobcard_edit']);
    Route::put('jobcard_update', ['as' => 'jobcard.update', 'uses' => 'JobcardController@update']);
    Route::post('jobcard_serviceinsert', ['as' => 'jobcard.service_insert', 'uses' => 'JobcardController@service_insert']);
    Route::get('jobcard_servicelist', ['as' => 'jobcard.service_list', 'uses' => 'JobcardController@load_jobcardservice_list']);
    Route::get('clear/{page}', ['as' => 'pages.clear_tables', 'uses' => 'ClearController@cleartables']);
    Route::get('jobcard_delete/{id}', ['as' => 'jobcard.jobcard_delete', 'uses' => 'JobcardController@delete']);
    Route::post('jobcard_delete', ['as' => 'jobcard_delete', 'uses' => 'JobcardController@fielddelete']);
    Route::post('jobcard_serviceupdate', ['as' => 'jobcard.service_update', 'uses' => 'JobcardController@service_update']);
    Route::post('jobcard_delete_each', ['as' => 'jobcard_delete_each', 'uses' => 'JobcardController@fielddelete_each']);
    Route::get('jobcard_servicelist_edit', ['as' => 'jobcard.service_list_edit', 'uses' => 'JobcardController@load_jobcardservice_list_edit']);
    Route::get('jobcard_view/{id}', ['as' => 'jobcard.jobcard_view', 'uses' => 'JobcardController@jobcard_view_each']);
    Route::get('jobcard_servicelist_view', ['as' => 'jobcard.service_list_view', 'uses' => 'JobcardController@load_jobcardservice_list_view']);
    Route::post('jobcard_autocomplete', ['as' => 'jobcard.searchnumber', 'uses' => 'JobcardController@load_jobcard_number']);
    Route::get('jobcard_updatestatus', ['as' => 'jobcard.updatestatus', 'uses' => 'JobcardController@updatestatus']);
    Route::post('jobcard_partsinsert', ['as' => 'jobcard.parts_insert', 'uses' => 'JobcardController@parts_insert']);
    Route::post('edit_jobcardservice', ['as' => 'jobcard.service_edit', 'uses' => 'JobcardController@cart_edit']);
    Route::get('jobcard_history', ['as' => 'jobcard.jobcard_history', 'uses' => 'JobcardController@jobcard_history_view']);
    Route::get('jobcard_history_view/{id}', ['as' => 'jobcard.jobcard_history_view', 'uses' => 'JobcardController@jobcard_history_pageview']);
    Route::post('jobcard_history_filter', ['as' => 'jobcard.history_filter', 'uses' => 'JobcardController@filter_history']);
    Route::get('jobcard_report', ['as' => 'jobcard.jobcard_report', 'uses' => 'JobcardController@jobcard_report_view']);
    Route::get('jobcard_reviews', ['as' => 'jobcard.jobcard_reviews', 'uses' => 'JobcardController@jobcard_reviews_view']);
    Route::post('jobcard_review_filter', ['as' => 'jobcard.review_filter', 'uses' => 'JobcardController@filter_review']);
    Route::post('export', 'JobcardController@export');

    //products
    Route::get('products', ['as' => 'products.products', 'uses' => 'ProductController@products_view']);
    Route::get('products_add', function() {
        return view('products.products_add');
    });
    Route::put('products_insert', ['as' => 'products.insert', 'uses' => 'ProductController@insert']);
    Route::get('products_edit/{id}', ['as' => 'products.products_edit', 'uses' => 'ProductController@products_edit']);
    Route::put('products_update', ['as' => 'products.update', 'uses' => 'ProductController@update']);

    //services
    Route::get('services', ['as' => 'services.services', 'uses' => 'ServiceController@services_view']);
    Route::get('services_add', ['as' => 'services.services_add', 'uses' => 'ServiceController@services_add']);
    Route::post('services_insert', ['as' => 'services.insert', 'uses' => 'ServiceController@insert']);

    //configuration
    Route::get('vendor_configuration/{id}', ['as' => 'configuration.vendor_configuration', 'uses' => 'ConfigurationController@vendorconfig_view']);
    Route::get('config_add', ['as' => 'configuration.config_add', 'uses' => 'ConfigurationController@config_add']);
    Route::put('config_insert', ['as' => 'configuration.insert', 'uses' => 'ConfigurationController@insert']);
    Route::get('config_update', ['as' => 'configuration.config_update', 'uses' => 'ConfigurationController@config_update']);
    Route::get('config_view', ['as' => 'configuration.config_view', 'uses' => 'ConfigurationController@config_view']);
    Route::get('main_configuration', ['as' => 'configuration.main_configuration', 'uses' => 'ConfigurationController@mainconfig_view']);
    Route::get('config_main_update', ['as' => 'configuration.config_main_update', 'uses' => 'ConfigurationController@config_main_update']);
    Route::post('vendor_config_list', ['as' => 'configuration.vendor_config_list', 'uses' => 'ConfigurationController@vendor_config_list']);
     //vendor services
     Route::get('vendorservice/{mode}', ['as' => 'vendor_service.vendorservice', 'uses' => 'VendorServiceController@services_view']);
     Route::get('vendorservice_add/{mode}', ['as' => 'vendor_service.vendorservice_add', 'uses' => 'VendorServiceController@services_add']);
     Route::post('vendorservice_insert', ['as' => 'vendor_service.insert', 'uses' => 'VendorServiceController@insert']);

     Route::post('block_vendor_login', ['as' => 'block_vendor_login', 'uses' => 'VendorController@block_vendor_login']);
});

