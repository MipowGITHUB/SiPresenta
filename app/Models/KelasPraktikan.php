<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasPraktikan extends Model
{
    Use HasFactory, SoftDeletes;
    protected $fillable = ['praktikan_id', 'praktikum_id'];

    public function praktikan()
    {
        return $this->belongsTo(Praktikan::class);
    }

    public function praktikum()
    {
        return $this->belongsTo(Praktikum::class);
    }
}
