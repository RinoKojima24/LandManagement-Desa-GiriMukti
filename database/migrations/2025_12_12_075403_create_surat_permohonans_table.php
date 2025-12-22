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
        Schema::create('surat_permohonans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nama')->nullable();
            $table->string('nik')->nullable();
            $table->string('tempat')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->date('agama')->nullable();

            $table->string('pekerjaan')->nullable();
            $table->text('alamat')->nullable();

            $table->string('jalan')->nullable();
            $table->string('rt_rw')->nullable();

            $table->unsignedBigInteger('rt_id')->nullable();

            $table->string('panjang')->nullable();
            $table->string('lebar')->nullable();
            $table->string('luas')->nullable();

            $table->string('sebelah_utara')->nullable();
            $table->string('sebelah_timur')->nullable();
            $table->string('sebelah_selatan')->nullable();
            $table->string('sebelah_barat')->nullable();

            $table->text('kondisi_fisik')->nullable();
            $table->text('dasar_perolehan')->nullable();

            $table->string('tahun_dikuasai')->nullable();
            $table->enum('status', ['pending', 'verifikasi', 'reject'])->default('pending');

            $table->string('ktp')->nullable();
            $table->string('dokumen_pendukung')->nullable();




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_permohonans');
    }
};
