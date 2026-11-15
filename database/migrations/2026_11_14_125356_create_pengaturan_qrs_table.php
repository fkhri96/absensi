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
    Schema::create('pengaturan_qrs', function (Blueprint $table) {
        $table->id();
        $table->integer('durasi_detik')->default(60);
        $table->boolean('refresh_otomatis')->default(true);
        $table->boolean('validasi_lokasi')->default(false);
        $table->string('latitude')->nullable();
        $table->string('longitude')->nullable();
        $table->integer('radius_meter')->default(100);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_qrs');
    }
};
