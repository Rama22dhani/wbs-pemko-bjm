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
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->string('pihak_penindak')->nullable()->after('tindak_lanjut');
            $table->date('tanggal_tindak_lanjut')->nullable()->after('pihak_penindak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn(['pihak_penindak', 'tanggal_tindak_lanjut']);
        });
    }
};
