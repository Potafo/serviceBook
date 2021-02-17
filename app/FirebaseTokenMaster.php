<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FirebaseTokenMaster extends Model
{
    protected $fillable = [
     'user_token', 'sl_no', 'device', 'device_id', 'fb_token', 'app_version',
    ];
    protected $table = 'firebasetoken_master';
    protected $primaryKey = 'id';
    public $timestamps =false;
    protected $dates = ['deleted_at'];
}
