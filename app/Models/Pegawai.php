<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'nama_pegawai',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'status_kepegawaian',
        'asal_instansi',
        'jabatan',
        'nomor_hp',
        'status_aktif',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}