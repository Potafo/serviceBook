<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobcardServices extends Model
{
    protected $fillable = [
        'jobcard_reference', 'jobcard_number', 'product_id',
    ];
    protected $table = 'jobcard_services';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
