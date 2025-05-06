<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengalaman_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('pengalaman_id');
            $table->unsignedBigInteger('keahlian_id');
            $table->timestamps();

            $table->primary(['pengalaman_id', 'keahlian_id']);

            $table->foreign('pengalaman_id')->references('pengalaman_id')->on('pengalaman_mahasiswa')->onDelete('cascade');
            $table->foreign('keahlian_id')->references('keahlian_id')->on('keahlian')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengalaman_tag');
    }
};
