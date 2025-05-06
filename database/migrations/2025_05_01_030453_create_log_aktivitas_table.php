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
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('pengajuan_id');
            $table->date('tanggal_log');
            $table->text('aktivitas');
            $table->text('kendala')->nullable();
            $table->text('solusi')->nullable();
            $table->decimal('jam_kegiatan', 4, 2);
            $table->text('feedback_dosen')->nullable();
            $table->timestamps();

            $table->foreign('pengajuan_id')->references('pengajuan_id')->on('pengajuan_magang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};