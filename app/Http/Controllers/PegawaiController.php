<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    /**
     * Menampilkan Seluruh Master Data Pegawai ASN
     */
    public function index(Request $request)
    {
        $query = Pegawai::with('user');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama_pegawai', 'like', "%{$cari}%")
                  ->orWhere('nip', 'like', "%{$cari}%")
                  ->orWhere('asal_instansi', 'like', "%{$cari}%")
                  ->orWhere('jabatan', 'like', "%{$cari}%");
            });
        }

        $dataMasterPegawai = $query->latest()->paginate(10);

        return view('admin.pegawai.index', compact('dataMasterPegawai'));
    }

    /**
     * Menyimpan Data Pegawai ASN Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'            => 'nullable|exists:users,id',
            'nip'                => 'required|string|max:18|unique:pegawais,nip',
            'nama_pegawai'       => 'required|string|max:255',
            'jenis_kelamin'      => 'nullable|in:Laki-laki,Perempuan',
            'tempat_lahir'       => 'nullable|string|max:255',
            'tanggal_lahir'      => 'nullable|date',
            'alamat'             => 'nullable|string',
            'status_kepegawaian' => 'required|in:PNS,PPPK,CPNS,Honorer',
            'asal_instansi'      => 'required|string|max:255',
            'jabatan'            => 'required|string|max:255',
            'nomor_hp'           => 'nullable|string|max:20',
            'status_aktif'       => 'required|in:Aktif,Nonaktif',
        ]);

        Pegawai::create($validated);

        return redirect()->back()->with('success', 'Master data pegawai ASN berhasil ditambahkan.');
    }

    /**
     * Memperbarui Data Pegawai ASN
     */
    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $validated = $request->validate([
            'user_id'            => 'nullable|exists:users,id',
            'nip'                => 'required|string|max:18|unique:pegawais,nip,' . $pegawai->id,
            'nama_pegawai'       => 'required|string|max:255',
            'jenis_kelamin'      => 'nullable|in:Laki-laki,Perempuan',
            'tempat_lahir'       => 'nullable|string|max:255',
            'tanggal_lahir'      => 'nullable|date',
            'alamat'             => 'nullable|string',
            'status_kepegawaian' => 'required|in:PNS,PPPK,CPNS,Honorer',
            'asal_instansi'      => 'required|string|max:255',
            'jabatan'            => 'required|string|max:255',
            'nomor_hp'           => 'nullable|string|max:20',
            'status_aktif'       => 'required|in:Aktif,Nonaktif',
        ]);

        $pegawai->update($validated);

        return redirect()->back()->with('success', 'Master data pegawai ASN berhasil diperbarui.');
    }

    /**
     * Menghapus Data Pegawai ASN
     */
    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();

        return redirect()->back()->with('success', 'Master data pegawai ASN berhasil dihapus.');
    }
}