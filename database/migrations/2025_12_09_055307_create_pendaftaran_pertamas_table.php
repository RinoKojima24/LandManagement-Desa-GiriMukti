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
        Schema::create('pendaftaran_pertamas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peta_tanah_id');
            $table->string('hak')->nullable();
            $table->string('nomor')->nullable();
            $table->string('desa_kel')->nullable();
            $table->string('tanggal_berakhirnya_hak')->nullable();

            $table->string('nib')->nullable();
            $table->string('letak_tanah')->nullable();

            $table->string('konversi')->nullable();
            $table->string('tgl_konversi')->nullable();
            $table->string('no_konversi')->nullable();


            $table->string('pemberian_hak')->nullable();
            $table->string('tgl_pemberian_hak')->nullable();
            $table->string('no_pemberian_hak')->nullable();

            $table->string('pemecahan')->nullable();
            $table->string('tgl_pemecahan')->nullable();
            $table->string('no_pemecahan')->nullable();

            $table->string('tgl_surat_ukur')->nullable();
            $table->string('no_surat_ukur')->nullable();
            $table->string('luas_surat_ukur')->nullable();

            $table->string('petunjuk')->nullable();
            $table->string('nama_pemegang_hak')->nullable();
            $table->string('tanggal_lahir_akta_pendirian')->nullable();












            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_pertamas');
    }
};
