<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    protected $fillable = [
        'jobcard_number', 'star_rating', 'review', 'date', 'status',
    ];
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
