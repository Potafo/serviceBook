<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name', 'contact_number', 'vendor_id','email'
    ];
    protected $table = 'customers';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
