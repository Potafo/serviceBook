<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RenewalList extends Model
{
    protected $table = 'renewal_list';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
