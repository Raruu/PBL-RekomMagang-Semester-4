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
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->id('perusahaan_id');
            $table->unsignedBigInteger('lokasi_id');
            $table->string('nama_perusahaan', 100);
            $table->unsignedBigInteger('bidang_id');
            $table->string('website', 255)->nullable();
            $table->string('kontak_email', 100)->nullable();
            $table->string('kontak_telepon', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

             $table->foreign('bidang_id')->references('bidang_id')->on('bidang_industri')->onDelete('cascade');
            $table->foreign('lokasi_id')->references('lokasi_id')->on('lokasi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaan');
    }
};