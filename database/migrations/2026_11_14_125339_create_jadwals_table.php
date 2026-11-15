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
    Schema::create('jadwals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
        $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
        $table->time('jam_mulai');
        $table->time('jam_selesai');
        $table->string('ruang');
        $table->string('tahun_akademik')->default('2026/2027 Ganjil');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
