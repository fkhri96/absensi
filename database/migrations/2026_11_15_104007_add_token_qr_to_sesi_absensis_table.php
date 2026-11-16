<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sesi_absensis', function (Blueprint $table) {
            if (!Schema::hasColumn('sesi_absensis', 'token_qr')) {
                $table->string('token_qr')->nullable()->unique()->after('materi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sesi_absensis', function (Blueprint $table) {
            if (Schema::hasColumn('sesi_absensis', 'token_qr')) {
                $table->dropColumn('token_qr');
            }
        });
    }
};