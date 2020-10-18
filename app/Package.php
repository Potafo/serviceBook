<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'type', 'days','amount','status',
    ];
    protected $table = 'package';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
