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
        Schema::create('dikeluarkan_surat_ukurs', function (Blueprint $table) {
            $table->id();
            $table->string('surat_ukur_id');
            $table->string('tanggal');
            $table->string('nomor');
            $table->string('luas');
            $table->string('nomor_hak');
            $table->string('sisa_luas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dikeluarkan_surat_ukurs');
    }
};
