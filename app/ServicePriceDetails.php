<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicePriceDetails extends Model
{
    protected $fillable = [
        'service_id', 'actual_price', 'offer_price',  'tax_sgst', 'tax_cgst', 'changed_by', 'date',
    ];
    protected $table = 'service_pricedetails';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
