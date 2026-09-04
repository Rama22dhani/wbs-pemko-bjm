<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_instansi',
        'singkatan'
    ];

    public function pegawais()
    {
        return $this->hasMany(Pegawai::class, 'instansi_id');
    }
}
