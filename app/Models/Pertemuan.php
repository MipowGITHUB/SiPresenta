<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pertemuan extends Model
{
    Use HasFactory, SoftDeletes;
    protected $fillable = ['praktikum_id', 'pertemuan_ke', 'modul', 'kegiatan', 'keterangan', 'tanggal'];

    public function praktikum()
    {
        return $this->belongsTo(Praktikum::class);
    }

    public function kehadiranPraktikan()
    {
        return $this->hasMany(KehadiranPraktikan::class);
    }

    public function kehadiranAsisten()
    {
        return $this->hasMany(KehadiranAsisten::class);
    }
}
