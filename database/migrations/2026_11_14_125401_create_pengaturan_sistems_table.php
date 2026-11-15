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
    Schema::create('pengaturan_sistems', function (Blueprint $table) {
        $table->id();
        $table->string('nama_kampus')->default('Universitas Kampus');
        $table->string('alamat_kampus')->nullable();
        $table->string('tahun_akademik_aktif')->default('2026/2027');
        $table->enum('semester_aktif', ['Ganjil', 'Genap'])->default('Ganjil');
        $table->integer('minimal_kehadiran')->default(75);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sistems');
    }
};
