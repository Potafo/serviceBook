<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = [
        'name', 'active','notification','display_order',
    ];
    protected $table = 'status';
    protected $primaryKey = 'id';
    public $timestamps =false;

}
