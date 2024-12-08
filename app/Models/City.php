<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';
    protected $primaryKey = 'city_id';

    protected $fillable = ['city_name', 'province_id', 'region_id'];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
}
