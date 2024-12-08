<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiseaseExtent extends Model
{
    use HasFactory;

    protected $table = 'disease_extents';
    protected $primaryKey = 'extent_id';
}
