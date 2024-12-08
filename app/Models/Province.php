<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces';
    protected $primaryKey = 'province_id';

    protected $fillable = ['province_name', 'region_id'];

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'province_id');
    }

    public function municipalities()
    {
        return $this->hasMany(Municipality::class, 'province_id');
    }
}
