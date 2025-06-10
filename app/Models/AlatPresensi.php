<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlatPresensi extends Model
{
    Use HasFactory, SoftDeletes;
    
    protected $fillable = ['device_id', 'lokasi'];
    protected $table = 'alat_presensis';
}
