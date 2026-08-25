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
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tiket')->unique(); 
            
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('nama_pelapor')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->string('email')->nullable();

            $table->string('judul_laporan'); 
            $table->text('isi_laporan');     
            $table->date('tanggal_kejadian');
            $table->string('lokasi_kejadian'); 
            $table->string('kategori_laporan'); 
            
            $table->string('lampiran_bukti')->nullable();
            $table->enum('status', ['masuk', 'verifikasi', 'investigasi', 'selesai', 'ditolak'])->default('masuk');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
