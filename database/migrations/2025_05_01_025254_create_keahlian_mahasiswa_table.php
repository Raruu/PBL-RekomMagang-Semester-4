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
        Schema::create('keahlian_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('keahlian_id');
            $table->enum('tingkat_kemampuan', ['pemula', 'menengah', 'mahir', 'ahli']);
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('profil_mahasiswa')->onDelete('cascade');
            $table->foreign('keahlian_id')->references('keahlian_id')->on('keahlian')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keahlian_mahasiswa');
    }
};