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
        // Modifying ENUM directly via DB statement since Schema builder doesn't support changing ENUM smoothly in some cases
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE pengaduans MODIFY COLUMN status ENUM('masuk', 'verifikasi', 'investigasi', 'tindak_lanjut', 'selesai', 'ditolak') DEFAULT 'masuk'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE pengaduans MODIFY COLUMN status ENUM('masuk', 'verifikasi', 'investigasi', 'selesai', 'ditolak') DEFAULT 'masuk'");
    }
};
