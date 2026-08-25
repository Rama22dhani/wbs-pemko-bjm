<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom baru di tabel pengaduans
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->text('pesan_susulan')->nullable()->after('status');
            $table->string('lampiran_susulan')->nullable()->after('pesan_susulan');
        });

        // 2. Hapus tabel lama yang tidak terpakai
        Schema::dropIfExists('tanggapans');
        Schema::dropIfExists('pelanggarans');
    }

    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn(['pesan_susulan', 'lampiran_susulan']);
        });
    }
};