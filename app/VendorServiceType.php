<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorServiceType extends Model
{
    protected $fillable = [
        'service_type', 'vendor_id', 'status'
    ];
    protected $table = 'vendor_servicetype';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
