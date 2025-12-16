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
        Schema::create('berita_acaras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('surat_permohonan_id');
            $table->date('tanggal_dilaksanakan');

            $table->string('nama_1')->nullable();
            $table->string('nip_1')->nullable();
            $table->string('jabatan_1')->nullable();
            $table->string('tugas_1')->nullable();

            $table->string('nama_2')->nullable();
            $table->string('nip_2')->nullable();
            $table->string('jabatan_2')->nullable();
            $table->string('tugas_2')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acaras');
    }
};
