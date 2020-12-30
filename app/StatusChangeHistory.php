<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusChangeHistory extends Model
{
    protected $fillable = [
        'jobcard_number', 'from_status','to_status','change_by','date',
    ];
    protected $table = 'status_change_history';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
