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
        Route::get('packages', ['as' => 'pages.packages', 'uses' => 'PackageController@packages_view']);
        Route::get('load_package', 'PackageController@load_package')->name('load_package');
        Route::get('vendors', ['as' => 'vendors.vendors', 'uses' => 'VendorController@vendors_view']);
        Route::get('vendor_view/{id}', ['as' => 'vendors.vendor_view', 'uses' => 'VendorController@vendors_view_fulllist']);
        Route::get('vendor_edit/{id}', ['as' => 'vendors.vendor_edit', 'uses' => 'VendorController@vendors_edit']);
        Route::get('vendor_add', ['as' => 'vendors.vendor_add', 'uses' => 'VendorController@vendors_add']);

});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
    Route::put('vendors_i', ['as' => 'vendors.insert', 'uses' => 'VendorController@insert']);
    Route::put('vendors_u', ['as' => 'vendors.update', 'uses' => 'VendorController@update']);
});

