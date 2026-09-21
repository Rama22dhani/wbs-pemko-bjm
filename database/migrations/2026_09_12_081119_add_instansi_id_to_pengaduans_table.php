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
            $table->unsignedBigInteger('instansi_id')->nullable()->after('kategori_id');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
            $table->dropColumn('instansi_id');
        });
    }
};
