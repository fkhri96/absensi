<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Pengaturan Lokasi Kampus (Dikelola Admin)
        if (!Schema::hasTable('pengaturan_lokasis')) {
            Schema::create('pengaturan_lokasis', function (Blueprint $table) {
                $table->id();
                $table->string('nama_lokasi')->default('Kampus Utama');
                $table->decimal('latitude', 10, 8);
                $table->decimal('longitude', 11, 8);
                $table->integer('radius_meter')->default(100);
                $table->boolean('wajib_gps')->default(true);
                $table->timestamps();
            });
        }

        // 2. Kolom koordinat & jarak scan pada tabel absensis
        Schema::table('absensis', function (Blueprint $table) {
            if (!Schema::hasColumn('absensis', 'latitude_scan')) {
                $table->decimal('latitude_scan', 10, 8)->nullable()->after('status');
            }
            if (!Schema::hasColumn('absensis', 'longitude_scan')) {
                $table->decimal('longitude_scan', 11, 8)->nullable()->after('latitude_scan');
            }
            if (!Schema::hasColumn('absensis', 'jarak_meter')) {
                $table->integer('jarak_meter')->nullable()->after('longitude_scan');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_lokasis');
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn(['latitude_scan', 'longitude_scan', 'jarak_meter']);
        });
    }
};