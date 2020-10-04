<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\UserType;

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
}
