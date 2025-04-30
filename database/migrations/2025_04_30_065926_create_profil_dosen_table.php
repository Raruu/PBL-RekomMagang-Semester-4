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
        Schema::create('profil_dosen', function (Blueprint $table) {
            $table->id('dosen_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lokasi_id')->constrained('lokasi')->onDelete('restrict');
            $table->string('nama_lengkap', 100);
            $table->string('nip', 30)->unique();
            $table->foreignId('program_id')->constrained('program_studi')->onDelete('restrict');
            // $table->text('minat_penelitian')->nullable();
            $table->string('nomor_telepon', 20)->nullable();
            $table->string('foto_profil', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_dosen');
    }
};
