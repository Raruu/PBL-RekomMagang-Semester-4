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
        Schema::create('lowongan_magang', function (Blueprint $table) {
            $table->id('lowongan_id');
            $table->unsignedBigInteger('perusahaan_id');
            $table->unsignedBigInteger('lokasi_id');
            $table->string('judul_lowongan', 255);
            $table->string('judul_posisi', 100);
            $table->text('deskripsi')->nullable();
            $table->decimal('gaji', 10, 2)->nullable();
            $table->integer('kuota')->default(1);
            $table->enum('tipe_kerja_lowongan', ['remote', 'onsite', 'hybrid'])->default('onsite');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->date('batas_pendaftaran')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('perusahaan_id')->references('perusahaan_id')->on('perusahaan');
            $table->foreign('lokasi_id')->references('lokasi_id')->on('lokasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lowongan_magang');
    }
};