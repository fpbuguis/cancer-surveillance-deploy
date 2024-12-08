<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;

    protected $table = 'specialties';

    // If you want, you can specify the fillable attributes
    protected $fillable = ['specialty_name', 'specialty_description'];
    protected $primaryKey = 'specialty_id';

}
