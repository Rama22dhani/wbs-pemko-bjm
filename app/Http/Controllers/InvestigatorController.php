<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvestigatorController extends Controller
{
    /**
     * Menampilkan Dashboard Daftar Tugas Investigasi Kasus
     */
    public function index()
    {
        // Mengambil kasus yang didisposisikan khusus kepada investigator yang sedang login
        $pengaduans = Pengaduan::where('investigator_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('investigator.dashboard', compact('pengaduans'));
    }

    /**
     * Menampilkan Detail Kasus dan Lembar Kerja Pemeriksaan
     */
    public function show($id)
    {
        $pengaduan = Pengaduan::with(['user', 'investigator'])->findOrFail($id);

        // Keamanan: Pastikan hanya investigator yang ditugaskan yang dapat mengakses
        if ($pengaduan->investigator_id !== Auth::id()) {
            return redirect()->route('investigator.dashboard')->with('error', 'Akses Ditolak: Anda tidak ditugaskan untuk menangani kasus ini.');
        }

        return view('investigator.detail', compact('pengaduan'));
    }

    /**
     * Menyimpan Hasil Investigasi / Kertas Kerja Pemeriksaan Lapangan
     */
    public function update(Request $request, $id)
    {
        $kasus = Pengaduan::findOrFail($id);

        if ($kasus->investigator_id !== Auth::id()) {
            return redirect()->route('investigator.dashboard')->with('error', 'Akses Ditolak.');
        }

        $validated = $request->validate([
            'fakta_lapangan'    => 'required|string',
            'pihak_terlibat'    => 'required|string',
            'kesimpulan'        => 'required|string',
            'bukti_investigasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('bukti_investigasi')) {
            if ($kasus->bukti_investigasi && Storage::disk('public')->exists($kasus->bukti_investigasi)) {
                Storage::disk('public')->delete($kasus->bukti_investigasi);
            }
            $validated['bukti_investigasi'] = $request->file('bukti_investigasi')->store('bukti_investigasi', 'public');
        }

        // Simpan data pemeriksaan dan perbarui status menjadi 'tindak_lanjut' (Menunggu Tindak Lanjut)
        $kasus->update(array_merge($validated, [
            'status' => 'tindak_lanjut',
        ]));

        return redirect()->route('investigator.dashboard')->with('success', 'Kertas kerja hasil pemeriksaan lapangan berhasil disimpan.');
    }
}