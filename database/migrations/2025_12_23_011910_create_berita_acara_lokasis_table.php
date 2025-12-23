<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('berita_acara_lokasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('berita_acara_id')->nullable();
            $table->string('kordinat_long')->nullable();
            $table->string('kordinat_lat')->nullable();
            $table->string('kordinat_sisi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acara_lokasis');
    }
};
