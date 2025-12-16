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
        Schema::create('surat_ukurs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peta_tanah_id');
            $table->string('nomor')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('desa')->nullable();
            $table->string('peta')->nullable();
            $table->string('nomor_peta')->nullable();
            $table->string('lembar')->nullable();
            $table->string('kotak')->nullable();
            $table->string('keadaan_tanah')->nullable();
            $table->string('tanda_tanda_batas')->nullable();
            $table->string('penunjukan_dan_penetapan_batas')->nullable();
            $table->string('hal_lain_lain')->nullable();

            $table->string('tgl_daftar_isian_208')->nullable();
            $table->string('no_daftar_isian_208')->nullable();

            $table->string('tgl_daftar_isian_302')->nullable();
            $table->string('no_daftar_isian_302')->nullable();

            $table->string('tgl_daftar_isian_307')->nullable();
            $table->string('no_daftar_isian_307')->nullable();

            $table->string('tanggal_penomoran_surat_ukur')->nullable();

            $table->string('nomor_surat_ukur')->nullable();
            $table->string('nomor_hak')->nullable();
















            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_ukurs');
    }
};
