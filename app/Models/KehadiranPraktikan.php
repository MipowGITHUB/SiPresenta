<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KehadiranPraktikan extends Model
{
    Use HasFactory, SoftDeletes;

    protected $fillable = ['praktikan_id', 'pertemuan_id', 'alat_presensi_id', 'keterangan', 'waktu_masuk', 'waktu_keluar'];

    public function praktikan()
    {
        return $this->belongsTo(Praktikan::class);
    }

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }

}
