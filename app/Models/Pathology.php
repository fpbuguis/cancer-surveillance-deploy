<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pathology extends Model
{
    use HasFactory;

    protected $table = 'pathologies';
    protected $primaryKey = 'pathology_id';

    protected $fillable = [
        'ICDO32',
        'pathology_level',
        'term',
        'code_reference',
        'obs',
        'see_also',
        'see_note',
        'includes',
        'excludes',
        'other_text'
    ];

    public function histologies()
    {
        return $this->hasMany(Histology::class, 'pathology_id', 'pathology_id');
    }
}
