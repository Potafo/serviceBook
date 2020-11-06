<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\UserType;
use App\SalesExecutive;
use App\VendorCategory;
use App\VendorType;
use App\Package;
use App\ServiceType;
use App\Vendor;

use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
         $this->loadUsertype();
         $this->loadSalesExecutive();
         $this->loadVendorCategory();
         $this->loadVendorType();
         $this->loadPackages();
         $this->loadVendors();
         $this->loadServicetype();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
    private function loadUsertype() {
        view()->composer(['*'], function($view) {
            $usertype = UserType::select('type', 'id')
                ->where('status','Y')
                ->Where('type','!=' ,'Admin')
                ->get();
            $view->with('usertype', $usertype);
        });
    }
    private function loadSalesExecutive() {
        view()->composer(['*'], function($view) {
            $salesexec = SalesExecutive::select('name', 'id')
                ->where('status','Y')
                ->get();
            $view->with('salesexec', $salesexec);
        });
    }
    private function loadVendorCategory() {
        view()->composer(['*'], function($view) {
            $vendorcategory = VendorCategory::select('name', 'id')
                ->where('status','Y')
                ->get();
            $view->with('vendorcategory', $vendorcategory);
        });
    }
    private function loadVendorType() {
        view()->composer(['*'], function($view) {
            $vendortype = VendorType::select('name', 'id')
                ->where('status','Y')
                ->get();
            $view->with('vendortype', $vendortype);
        });
    }
    private function loadPackages() {
        view()->composer(['*'], function($view) {
            $packagetype = Package::select('type', 'id','days','amount')
                ->where('status','Y')
                ->where('id','!=',1)
                ->get();
            $view->with('packagetype', $packagetype);
        });
    }
    private function loadVendors() {
        view()->composer(['*'], function($view) {
            $vendor_list = Vendor::select('name', 'id')
                ->get();
            $view->with('vendor_list', $vendor_list);
        });
    }
    private function loadServicetype() {
        view()->composer(['*'], function($view) {
            $servicetype = ServiceType::select('name', 'id')
                ->get();
            $view->with('servicetype', $servicetype);
        });
    }
}
