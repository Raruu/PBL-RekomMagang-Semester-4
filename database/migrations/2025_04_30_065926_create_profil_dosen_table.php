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
            $table->unsignedBigInteger('dosen_id')->primary();
            $table->unsignedBigInteger('lokasi_id');
            $table->string('nama', 100);
            $table->string('nip', 30)->unique();
            $table->unsignedBigInteger('program_id');
            $table->text('minat_penelitian')->nullable();
            $table->string('nomor_telepon', 20)->nullable();
            $table->string('foto_profil', 255)->nullable();
            $table->timestamps();

            $table->foreign('dosen_id')->references('user_id')->on('user')->onDelete('cascade');
            $table->foreign('lokasi_id')->references('lokasi_id')->on('lokasi')->onDelete('cascade');
            $table->foreign('program_id')->references('program_id')->on('program_studi');
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