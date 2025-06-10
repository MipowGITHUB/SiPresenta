<?php

namespace App\Http\Controllers;

use App\Models\Asisten;
use Illuminate\Http\Request;

class AsistenKelasController extends Controller
{
    public function destroy($kelasId)
    {
        // Temukan asisten terkait (melalui relasi many-to-many)
        $asisten = Asisten::whereHas('praktikums', fn($q) => $q->where('id', $kelasId))->first();

        if ($asisten) {
            $asisten->praktikums()->detach($kelasId);
            return redirect()->back()->with('success', 'Kelas berhasil dihapus dari asisten.');
        }

        return redirect()->back()->with('error', 'Kelas tidak ditemukan atau tidak terkait.');
    }
}
