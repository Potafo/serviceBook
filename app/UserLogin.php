<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    protected $table = 'user_logindetails';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
