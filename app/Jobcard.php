<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobcard extends Model
{
    protected $fillable = [
        'jobcard_number', 'product_id', 'vendor_id','user_id','remarks',
    ];
    protected $table = 'job_card';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
