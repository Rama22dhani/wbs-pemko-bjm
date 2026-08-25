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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->string('nip')->unique();
            $table->string('nama_pegawai');
            $table->enum('status_kepegawaian', ['PNS', 'PPPK', 'CPNS', 'Honorer']);
            $table->string('asal_instansi');
            $table->string('jabatan');

            $table->string('nomor_hp')->nullable();
            $table->enum('status_aktif', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
