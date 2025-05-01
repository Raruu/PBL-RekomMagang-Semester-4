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
        Schema::create('keahlian_lowongan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lowongan_id');
            $table->unsignedBigInteger('keahlian_id');
            $table->enum('kemampuan_minimum', ['pemula', 'menengah', 'mahir', 'ahli'])->nullable();
            $table->timestamps();

            $table->foreign('lowongan_id')->references('lowongan_id')->on('lowongan_magang')->onDelete('cascade');
            $table->foreign('keahlian_id')->references('keahlian_id')->on('keahlian')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keahlian_lowongan');
    }
};