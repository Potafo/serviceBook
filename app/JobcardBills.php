<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobcardBills extends Model
{
    protected $fillable = [
        'jobcard_number', 'bill_amount', 'received_amount', 'discount_amount','vendor_status',
    ];
    protected $table = 'jobcard_bills';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
