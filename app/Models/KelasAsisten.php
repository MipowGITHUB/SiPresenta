<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasAsisten extends Model
{
    Use HasFactory, SoftDeletes;
    
    protected $fillable = ['asisten_id', 'praktikum_id'];

    public function asisten()
    {
        return $this->belongsTo(Asisten::class);
    }

    public function praktikum()
    {
        return $this->belongsTo(Praktikum::class);
    }
}
