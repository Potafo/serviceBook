<?php

namespace App\Http\Controllers;

use App\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
     /**
     * Display packages page
     *
     * @return \Illuminate\View\View
     */
    public function packages_view()
    {
        //$package=new Package;
        //$flights =Package::all();
        return view('pages.packages');
    }
}
