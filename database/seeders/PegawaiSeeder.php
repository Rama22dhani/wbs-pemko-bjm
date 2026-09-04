<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pegawai;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        $penggunaAkses = User::whereIn('peran', ['admin', 'investigator', 'verifikator'])->get();

        foreach ($penggunaAkses as $index => $user) {
            Pegawai::updateOrCreate(
                ['nip' => '19850512201001100' . ($index + 1)], 
                [
                    'user_id'            => $user->id, 
                    'nama_pegawai'       => $user->name, 
                    'status_kepegawaian' => 'PNS',
                    'asal_instansi'      => 'Dinas Kominfotik',
                    'jabatan'            => $user->peran == 'admin' ? 'Administrator Utama' : ($user->peran == 'verifikator' ? 'Verifikator Berkas' : 'Investigator Lapangan'),
                    'nomor_hp'           => '08123456789' . $index,
                    'status_aktif'       => 'Aktif',
                ]
            );
        }
    }
}