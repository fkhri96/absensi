<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Samakan enum status dengan kode aplikasi (alpha, bukan alpa).
        // Hanya dijalankan jika belum sesuai.
        $kolom = DB::selectOne("SHOW COLUMNS FROM absensis LIKE 'status'");
        if ($kolom && ! str_contains($kolom->Type, "'alpha'")) {
            DB::statement("UPDATE absensis SET status = 'alpha' WHERE status = 'alpa'");
            DB::statement("ALTER TABLE absensis MODIFY status ENUM('hadir','izin','sakit','alpha') NOT NULL DEFAULT 'hadir'");
        }

        // Kolom pencatat, hanya jika belum ada
        if (! Schema::hasColumn('absensis', 'dicatat_oleh')) {
            Schema::table('absensis', function (Blueprint $table) {
                $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        // Sengaja kosong agar data absensi QR tidak ikut terhapus.
    }
};