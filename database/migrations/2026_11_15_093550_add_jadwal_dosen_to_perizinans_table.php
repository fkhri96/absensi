<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perizinans', function (Blueprint $table) {
            $table->foreignId('jadwal_id')->nullable()->after('mahasiswa_id')->constrained('jadwals')->onDelete('cascade');
            $table->foreignId('dosen_id')->nullable()->after('jadwal_id')->constrained('dosens')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('perizinans', function (Blueprint $table) {
            $table->dropForeign(['jadwal_id']);
            $table->dropForeign(['dosen_id']);
            $table->dropColumn(['jadwal_id', 'dosen_id']);
        });
    }
};