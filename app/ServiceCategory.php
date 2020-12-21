<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $fillable = [
        'name',
    ];
    protected $table = 'service_category';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
