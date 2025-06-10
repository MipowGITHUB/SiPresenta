<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Praktikum extends Model
{
    Use HasFactory, SoftDeletes;

    protected $fillable = ['kelas', 'matkul', 'lab','hari', 'waktu_mulai', 'waktu_selesai'];

    public function pertemuans()
    {
        return $this->hasMany(Pertemuan::class);
    }

    public function kelasPraktikan()
    {
        return $this->hasMany(KelasPraktikan::class);
    }

    public function kelasAsisten()
    {
        return $this->hasMany(KelasAsisten::class);
    }

    public function asistens()
    {
        return $this->belongsToMany(Asisten::class, 'kelas_asistens', 'praktikum_id', 'asisten_id');
    }

    public function praktikans()
    {
        return $this->belongsToMany(Praktikan::class, 'kelas_praktikans', 'praktikum_id', 'asisten_id');
    }
}
