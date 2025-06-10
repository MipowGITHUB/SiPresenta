<?php

use Illuminate\Support\Facades\Route;
Route::delete('/asisten/kelas/{id}', [\App\Http\Controllers\AsistenKelasController::class, 'destroy'])
    ->name('hapus.kelas.asisten');

Route::get('/', function () {
    return view('welcome');
});
