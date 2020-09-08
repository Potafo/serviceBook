<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    //
    protected $table = 'vendor';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
