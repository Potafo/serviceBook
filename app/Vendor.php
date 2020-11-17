<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    //
    protected $fillable = [
        'name', 'address','tax_enabled','short_code','web_name','shortkey','image', 'location_lat', 'location_long', 'location_maplink', 'location_embed', 'description', 'website', 'mail_id',  'contact_number', 'refferal_by', 'first_package', 'last_renewal_date', 'current_package', 'digital_profile_status', 'category', 'type',
    ];

    protected $table = 'vendor';
    protected $primaryKey = 'id';
    public $timestamps =false;
}
