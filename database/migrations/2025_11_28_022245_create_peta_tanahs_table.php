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
        Schema::create('peta_tanahs', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_bidang');
            $table->unsignedBigInteger('surat_permohonan_id');
            $table->string('luas');
            $table->string('status');
            $table->string('panjang');
            $table->string('lebar');
            $table->string('peruntukan');

            $table->string('foto_denah');

            $table->string('skala');
            $table->string('penjelasan');
            $table->string('nama_jalan');

            $table->text('alamat');
            // $table->datetime('tanggal_pengecekan');






            $table->string('titik_kordinat');
            $table->text('titik_kordinat_polygon');


            // $table->string('titik_kordinat_1');
            // $table->string('titik_kordinat_2');
            // $table->string('titik_kordinat_3');
            // $table->string('titik_kordinat_4');
            // $table->string('titik_kordinat_lat');
            // $table->string('titik_utara_long');
            // $table->string('titik_utara_lat');
            // $table->string('titik_selatan_long');
            // $table->string('titik_selatan_lat');
            // $table->string('titik_barat_long');
            // $table->string('titik_barat_lat');
            // $table->string('titik_timur_long');
            // $table->string('titik_timur_lat');
            $table->date('tanggal_pengukuran');
            $table->string('foto_peta');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peta_tanahs');
    }
};
