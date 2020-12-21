<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'jobcard_reference', 'jobcard_number','actual_price', 'service_id',  'price', 'tax_percent', 'tax_amount', 'total_with_tax', 'total_without_tax','service_name'
    ];
    protected $table = 'cart';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
