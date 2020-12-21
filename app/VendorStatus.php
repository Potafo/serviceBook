<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorStatus extends Model
{
    protected $fillable = [
        'status_id', 'active',  'vendor_id', 'send_sms', 'send_email', 'display_order', 'ending_status'
    ];
    protected $table = 'vendor_status';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
