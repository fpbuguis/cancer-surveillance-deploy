<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $table = 'regions';
    protected $primaryKey = 'region_id';

    protected $fillable = ['region_name'];

    public function provinces()
    {
        return $this->hasMany(Province::class, 'region_id');
    }
}
