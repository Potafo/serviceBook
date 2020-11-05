<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'product_id', 'image','vendor_id',
    ];
    protected $table = 'products';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
