<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $table = 'municipalities';
    protected $primaryKey = 'municipality_id';

    protected $fillable = ['municipality_name', 'province_id', 'region_id'];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
}
