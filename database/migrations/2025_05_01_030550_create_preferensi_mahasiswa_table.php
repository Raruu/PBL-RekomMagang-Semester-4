<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('preferensi_mahasiswa', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id')->primary();
            $table->unsignedBigInteger('lokasi_id');
            $table->unsignedBigInteger('perusahaan_id')->nullable();
            $table->text('posisi_preferensi')->nullable();
            $table->enum('tipe_kerja_preferensi', ['onsite', 'remote', 'hybrid', 'semua'])->default('semua');
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('profil_mahasiswa')->onDelete('cascade');
            $table->foreign('lokasi_id')->references('lokasi_id')->on('lokasi')->onDelete('cascade');
            $table->foreign('perusahaan_id')->references('perusahaan_id')->on('perusahaan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preferensi_mahasiswa');
    }
};