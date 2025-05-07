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
        Schema::create('profil_mahasiswa', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id')->primary();
            $table->string('nama', 100);
            $table->string('nim', 20)->unique();
            $table->unsignedBigInteger('program_id');
            $table->integer('semester')->nullable();
            $table->decimal('ipk', 3, 2)->nullable();
            $table->string('nomor_telepon', 20)->nullable();
            $table->string('foto_profil', 255)->nullable();
            $table->string('file_cv', 255)->nullable();
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('user_id')->on('user')->onDelete('cascade');
            $table->foreign('program_id')->references('program_id')->on('program_studi');
            $table->foreign('nim')->references('username')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_mahasiswa');
    }
};
