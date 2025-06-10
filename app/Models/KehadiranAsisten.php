<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KehadiranAsisten extends Model
{
    Use HasFactory, SoftDeletes;

    protected $fillable = ['asisten_id', 'pertemuan_id', 'alat_rfid_id', 'keterangan', 'waktu_masuk', 'waktu_keluar'];

    public function asisten()
    {
        return $this->belongsTo(Asisten::class);
    }

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }
}
