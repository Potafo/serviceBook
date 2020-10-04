<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesExecutive extends Model
{
    protected $fillable = [
        'name', 'email',
    ];

    protected $table = 'sales_executive';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
