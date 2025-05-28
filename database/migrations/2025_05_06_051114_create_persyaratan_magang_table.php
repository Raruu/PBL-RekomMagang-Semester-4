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
        Schema::create('persyaratan_magang', function (Blueprint $table) {
            $table->id('persyaratan_id');
            $table->unsignedBigInteger('lowongan_id');
            $table->decimal('minimum_ipk', 3, 2);
            $table->text('deskripsi_persyaratan');
            $table->text('dokumen_persyaratan')->nullable();
            $table->boolean('pengalaman')->default(true);
            $table->timestamps();

            $table->foreign('lowongan_id')->references('lowongan_id')->on('lowongan_magang')->onDelete('cascade');
        });
    }

    /**a
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persyaratan_magang');
    }
};
