<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basis extends Model
{
    use HasFactory;

    protected $table = 'bases';
    protected $primaryKey = 'basis_id';
    public $timestamps = false; 

    protected $fillable = [
        'basis_name',
        'basis_category'
    ];
}
