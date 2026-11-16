<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            if (!Schema::hasColumn('jadwals', 'dosen_id')) {
                $table->foreignId('dosen_id')->nullable()->after('mata_kuliah_id')->constrained('dosens')->nullOnDelete();
            }
            if (!Schema::hasColumn('jadwals', 'status_mengajar')) {
                $table->enum('status_mengajar', ['aktif', 'izin', 'sakit', 'dibatalkan'])->default('aktif')->after('ruang');
            }
            if (!Schema::hasColumn('jadwals', 'keterangan_dosen')) {
                $table->string('keterangan_dosen')->nullable()->after('status_mengajar');
            }
            if (!Schema::hasColumn('jadwals', 'tanggal_libur')) {
                $table->date('tanggal_libur')->nullable()->after('keterangan_dosen');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            if (Schema::hasColumn('jadwals', 'dosen_id')) {
                $table->dropForeign(['dosen_id']);
                $table->dropColumn('dosen_id');
            }
            foreach (['status_mengajar', 'keterangan_dosen', 'tanggal_libur'] as $col) {
                if (Schema::hasColumn('jadwals', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};