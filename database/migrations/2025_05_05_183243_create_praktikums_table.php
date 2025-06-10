<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('praktikums', function (Blueprint $table) {
            $table->id();
            $table->string('kelas');
            $table->string('matkul');
            $table->string('lab');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('praktikums');
    }
};
