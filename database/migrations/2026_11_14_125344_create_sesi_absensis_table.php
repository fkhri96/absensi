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
    Schema::create('sesi_absensis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
        $table->integer('pertemuan_ke');
        $table->string('materi');
        $table->date('tanggal');
        $table->enum('status', ['buka', 'tutup'])->default('buka');
        $table->string('qr_token')->nullable()->unique();
        $table->timestamp('qr_expired_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_absensis');
    }
};
