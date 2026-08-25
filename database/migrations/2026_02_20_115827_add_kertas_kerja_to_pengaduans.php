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
            $table->text('fakta_lapangan')->nullable()->after('hasil_investigasi');
            $table->text('pihak_terlibat')->nullable()->after('fakta_lapangan');
            $table->text('kesimpulan')->nullable()->after('pihak_terlibat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn(['fakta_lapangan', 'pihak_terlibat', 'kesimpulan']);
        });
    }
};
