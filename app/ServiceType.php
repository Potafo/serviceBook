<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    protected $fillable = [
        'name', 'status',
    ];
    protected $table = 'service_type';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
