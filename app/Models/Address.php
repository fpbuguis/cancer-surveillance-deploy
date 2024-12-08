<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';
    protected $primaryKey = 'address_id';
    public $timestamps = false; 


    protected $fillable = [
        'address_number',
        'address_street',
        'address_brgy',
        'address_zipcode',
        'region_id',
        'province_id',
        'city_id',
        'municipality_id',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }
}
