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
        Schema::create('kehadiran_asistens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asisten_id')->constrained()->onDelete('cascade');
            $table->foreignId('pertemuan_id')->constrained()->onDelete('cascade');
            $table->string('alat_rfid_id')->nullable(); // tanpa FK
            $table->enum('keterangan', ['Hadir', 'Izin', 'Alpha']);
            $table->time('waktu_masuk')->nullable();
            $table->time('waktu_keluar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_asistens');
    }
};
