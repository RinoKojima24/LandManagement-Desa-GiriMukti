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
        Schema::create('pernyataan_pemasangan_tendas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('surat_permohonan_id');
            $table->string('sebelah_utara_nama')->nullable();
            $table->string('sebelah_utara_nik')->nullable();

            $table->string('sebelah_timur_nama')->nullable();
            $table->string('sebelah_timur_nik')->nullable();

            $table->string('sebelah_selatan_nama')->nullable();
            $table->string('sebelah_selatan_nik')->nullable();

            $table->string('sebelah_barat_nama')->nullable();
            $table->string('sebelah_barat_nik')->nullable();

            $table->string('pembuat_pernyataan')->nullable();

            $table->string('rt')->nullable();
            $table->string('nama_ketua_rt')->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pernyataan_pemasangan_tendas');
    }
};
