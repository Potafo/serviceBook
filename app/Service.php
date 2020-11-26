<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name', 'type','product_id',
    ];
    protected $table = 'service';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
