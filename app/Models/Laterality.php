<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laterality extends Model
{
    use HasFactory;

    protected $table = "lateralities";
    protected $primaryKey = "laterality_id";
}
