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
        Schema::create('pernyataan_penguasaans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('surat_permohonan_id');
            $table->integer('tahun_kuasa')->nullable();
            $table->string('nama_peroleh')->nullable();
            $table->string('pembuat_pernyataan')->nullable();

            $table->string('nama_saksi_1')->nullable();
            $table->string('nik_saksi_1')->nullable();

            $table->string('nama_saksi_2')->nullable();
            $table->string('nik_saksi_2')->nullable();

            $table->string('nomor_rt')->nullable();
            $table->string('tanggal_rt')->nullable();
            $table->string('rt')->nullable();
            $table->string('nama_rt')->nullable();

            $table->string('nomor_kades')->nullable();
            $table->string('tanggal_kades')->nullable();

            $table->string('nomor_camat')->nullable();
            $table->string('tanggal_penajam')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pernyataan_penguasaans');
    }
};
