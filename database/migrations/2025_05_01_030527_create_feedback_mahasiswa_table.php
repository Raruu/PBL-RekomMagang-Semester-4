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
        Schema::create('feedback_mahasiswa', function (Blueprint $table) {
            $table->id('feedback_id');
            $table->unsignedBigInteger('pengajuan_id');
            $table->integer('rating')->nullable();
            $table->text('komentar')->nullable();
            $table->text('pengalaman_belajar')->nullable();
            $table->text('kendala')->nullable();
            $table->text('saran')->nullable();
            $table->timestamps();

            $table->foreign('pengajuan_id')->references('pengajuan_id')->on('pengajuan_magang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_mahasiswa');
    }
};