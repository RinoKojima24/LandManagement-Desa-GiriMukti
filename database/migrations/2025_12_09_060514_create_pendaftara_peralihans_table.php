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
        Schema::create('pendaftara_peralihans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peta_tanah_id');

            $table->string('sebab')->nullable();
            $table->string('nama')->nullable();
            $table->string('tanda_tangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftara_peralihans');
    }
};
