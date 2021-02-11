<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppConfiguration extends Model
{
    protected $fillable = [
        'type', 'name', 'value', 'config_id',
    ];
    protected $table = 'app_configuration';
    protected $primaryKey = 'id';
    public $timestamps =false;
    protected $dates = ['deleted_at'];
}
