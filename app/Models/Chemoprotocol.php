<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chemoprotocol extends Model
{
    use HasFactory;

    protected $table = 'chemoprotocols';
    protected $primaryKey = 'chemo_protocol_id';
}
