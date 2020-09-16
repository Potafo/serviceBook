<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Response;


class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        return view('users.index', ['users' => $model->paginate(15)]);
    }
    public function register_user(Request $request)
    {
        //`users`(`id`, `name`, `email`, `password`, `remember_token`, `user_type`, `created_at`, `updated_at`)
        $savestatus=0;
        $product= new User();
        $product->name               =$request['name'];
        $product->email              =$request['email'];
        $product->password          =Hash::make($request['password']);
        $product->remember_token          =$request['remember_token'];
        $product->user_type          =$request['user_type'];
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
}
