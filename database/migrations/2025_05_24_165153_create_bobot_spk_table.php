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
        Schema::create('bobot_spk', function (Blueprint $table) {
            $table->id('bobot_spk_id');
            $table->float('bobot')->default(0.0);
            $table->enum('jenis_bobot', ['IPK', 'keahlian', 'pengalaman', 'jarak', 'posisi'])->nullable();
            $table->float('bobot_prev')->default(0.0);
            $table->enum('jenis_bobot_prev', ['IPK', 'keahlian', 'pengalaman', 'jarak', 'posisi'])->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bobot_spk');
    }
};
