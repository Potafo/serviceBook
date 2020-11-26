<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $fillable = [
        'type', 'config_name', 'value', 'status', 'page_view',
    ];
    protected $table = 'configuration';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
