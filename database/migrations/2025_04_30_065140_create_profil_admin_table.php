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
        Schema::create('profil_admin', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id')->primary();
            $table->string('nama', 100);
            $table->string('nomor_telepon', 20)->nullable();
            $table->string('foto_profil', 255)->nullable();
            $table->timestamps();

            $table->foreign('admin_id')->references('user_id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_admin');
    }
};