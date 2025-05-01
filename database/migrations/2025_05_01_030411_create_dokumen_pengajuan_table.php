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
        Schema::create('dokumen_pengajuan', function (Blueprint $table) {
            $table->id('dokumen_id');
            $table->unsignedBigInteger('pengajuan_id');
            $table->string('jenis_dokumen', 50);
            $table->string('path_file', 255);
            $table->timestamps();

            $table->foreign('pengajuan_id')->references('pengajuan_id')->on('pengajuan_magang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_pengajuan');
    }
};