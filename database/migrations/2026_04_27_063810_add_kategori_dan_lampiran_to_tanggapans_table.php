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
        Schema::table('tanggapans', function (Blueprint $table) {
            $table->string('kategori_tanggapan')->nullable()->after('pesan');
            $table->string('lampiran_tambahan')->nullable()->after('kategori_tanggapan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tanggapans', function (Blueprint $table) {
            $table->dropColumn(['kategori_tanggapan', 'lampiran_tambahan']);
        });
    }
};
