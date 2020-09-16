<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobcard extends Model
{
    protected $table = 'job_card';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
