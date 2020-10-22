<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorType extends Model
{
    //
    protected $fillable = [
        'name', 'status',
    ];
    protected $table = 'vendor_type';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
