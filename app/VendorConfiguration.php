<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorConfiguration extends Model
{
    protected $fillable = [
        'vendor_id', 'tax_enabled',
    ];
    protected $table = 'vendor_configuration';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
