<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfigurationType extends Model
{
    protected $table = 'configuration_type';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
