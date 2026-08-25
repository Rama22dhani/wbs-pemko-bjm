<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\User;

class VerifikatorController extends Controller
{
    // 1. DASHBOARD VERIFIKATOR (Pending vs Riwayat)
    public function index()
    {
        // Tugas Masuk (Hanya yang statusnya 'pending')
        $pending = Pengaduan::where('status', 'masuk')
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Riwayat Kerja (Yang sudah diapa-apain)
        $riwayat = Pengaduan::whereIn('status', ['investigasi', 'selesai', 'ditolak'])
                            ->orderBy('updated_at', 'desc')
                            ->get();

        return view('verifikator.dashboard', compact('pending', 'riwayat'));
    }

    // 2. HALAMAN DETAIL VERIFIKASI
    public function show($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);
        
        // Ambil daftar user yang berperan sebagai 'investigator' untuk dropdown
        $investigators = User::where('peran', 'investigator')->get();

        return view('verifikator.detail', compact('pengaduan', 'investigators'));
    }

    // 3. LOGIKA PROSES (ACC)
    public function update(Request $request, $id)
    {
        $request->validate([
            'investigator_id' => 'required',
            'tingkat_pelanggaran' => 'required',
            'catatan_verifikator' => 'nullable|string',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        
        $pengaduan->update([
            'status' => 'investigasi', 
            'investigator_id' => $request->investigator_id,
            'tingkat_pelanggaran' => $request->tingkat_pelanggaran,
            'catatan_verifikator' => $request->catatan_verifikator,
        ]);

        return redirect()->route('verifikator.dashboard')
            ->with('success', 'Laporan di-ACC! Kasus dilimpahkan ke Investigator.');
    }

    // 4. LOGIKA TOLAK (REJECT)
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|min:5',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        
        $pengaduan->update([
            'status' => 'ditolak', 
            'alasan_penolakan' => $request->alasan_penolakan,
            'investigator_id' => null, 
        ]);

        return redirect()->route('verifikator.dashboard')
            ->with('success', 'Laporan DITOLAK. Status diperbarui.');
    }
}