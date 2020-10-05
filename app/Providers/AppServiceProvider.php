<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\UserType;
use App\SalesExecutive;
use App\VendorCategory;
use App\VendorType;

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
}
