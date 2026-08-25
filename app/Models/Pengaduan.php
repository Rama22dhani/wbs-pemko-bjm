<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $fillable = [
        // Data Pelapor & Laporan Awal
        'kode_tiket',
        'user_id',
        'nama_pelapor',
        'nip',
        'nomor_hp',
        'email',
        'judul_laporan',
        'isi_laporan',
        'tanggal_kejadian',
        'lokasi_kejadian',
        'kategori_laporan',
        'lampiran_bukti',
        
        // Data Verifikator
        'status',
        'tingkat_pelanggaran',
        'catatan_verifikator',
        'alasan_penolakan',
        'investigator_id',
        
        // Data Informasi/Bukti Tambahan (Revisi Pengganti Tabel Tanggapan)
        'pesan_susulan',
        'lampiran_susulan',
        
        // Data Investigator Lama & Baru
        'hasil_investigasi', 
        'fakta_lapangan',
        'pihak_terlibat',
        'kesimpulan',
        'bukti_investigasi',
        'tindak_lanjut',
        'pihak_penindak',
        'tanggal_tindak_lanjut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function investigator()
    {
        return $this->belongsTo(User::class, 'investigator_id');
    }
    
    // Fungsi tanggapans() sudah dihapus dari sini
}