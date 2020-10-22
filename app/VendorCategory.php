<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
    //
    protected $fillable = [
        'name', 'status',
    ];
    protected $table = 'vendor_category';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
