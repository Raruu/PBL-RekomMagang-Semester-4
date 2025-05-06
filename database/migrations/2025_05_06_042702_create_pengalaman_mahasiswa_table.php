<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengalaman_mahasiswa', function (Blueprint $table) {
            $table->id('pengalaman_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->string('nama_pengalaman', 255);
            $table->enum('tipe_pengalaman', ['lomba', 'kerja']);
            $table->string('path_file', 255)->nullable();
            $table->text('deskripsi_pengalaman');
            $table->date('periode_mulai')->nullable();
            $table->date('periode_selesai')->nullable();
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('profil_mahasiswa')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengalaman_mahasiswa');
    }
};
