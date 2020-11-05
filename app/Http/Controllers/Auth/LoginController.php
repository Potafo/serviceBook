<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use DateTime;
use DateTimeZone;
use App\User;
use App\UserLogin;
use App\Http\Requests\UserRequest;
use Session;
use App\Vendor;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function authenticated(Request $request,$user) {
        $timezone = 'ASIA/KOLKATA';
        $date = new DateTime('now', new DateTimeZone($timezone));
        $userlogin=new UserLogin;
        $userlogin->userid =$user->id;
        $userlogin->login_time =$date;
        $userlogin->save();
        Session::put('logged_user_id', $user->id);
        Session::put('logged_user_type', $user->user_type);
        if($user->user_type == '3') // vendor
        {
            $vendor_id=Vendor::select('vendor.id')
            ->where('vendor.user_id','=',$user->id)
            ->get();
            Session::put('logged_vendor_id', $vendor_id[0]->id);
        }else if($user->user_type == '1') // admin
        {
            Session::put('logged_vendor_id', '');
        }
    }
}
