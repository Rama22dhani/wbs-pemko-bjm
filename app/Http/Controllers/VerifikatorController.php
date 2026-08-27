<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VerifikatorController extends Controller
{
    public function index()
    {
        // DATA KASUS MASUK (Untuk Diverifikasi)
        $kasusMasuk = Pengaduan::where('status', 'masuk')->latest()->get();

        // DATA KASUS TERVERIFIKASI
        $dataKasus = Pengaduan::where('status', '!=', 'masuk')->latest()->get();

        // DATA INVESTIGASI
        $dataInvestigasi = Pengaduan::where(function($q) {
                                        $q->whereNotNull('fakta_lapangan')
                                        ->orWhereNotNull('hasil_investigasi');
                                    })
                                    ->latest()
                                    ->get();

        // ANTREAN INPUT TINDAK LANJUT
        $kasusPerluTindakLanjut = Pengaduan::whereNotNull('kesimpulan')
                                        ->whereNull('tindak_lanjut')
                                        ->latest()
                                        ->get();

        // DATA TINDAK LANJUT SELESAI
        $dataTindakLanjut = Pengaduan::whereNotNull('tindak_lanjut')
                                    ->latest()
                                    ->get();

        // DATA BUKTI
        $dataBukti = Pengaduan::where(function($q) {
                                $q->whereNotNull('lampiran_bukti')
                                ->orWhereNotNull('lampiran_susulan')
                                ->orWhereNotNull('bukti_investigasi');
                            })
                            ->latest()
                            ->get();

        // DATA PEGAWAI UNTUK INVESTIGATOR
        $dataPegawai = User::whereIn('peran', ['investigator'])->get();

        return view('verifikator.dashboard', compact(
            'kasusMasuk',
            'dataKasus',
            'dataInvestigasi',
            'kasusPerluTindakLanjut',
            'dataTindakLanjut',
            'dataBukti',
            'dataPegawai'
        ));
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);
        return view('verifikator.detail', compact('pengaduan')); 
    }

    public function verifikasiKasus(Request $request, $id)
    {
        $kasus = Pengaduan::findOrFail($id);

        if ($request->keputusan == 'tolak') {
            $kasus->update([
                'status' => 'ditolak',
                'catatan_verifikator' => $request->catatan_verifikator
            ]);
            $pesan = 'Laporan kasus berhasil ditolak & ditutup!';
        } else {
            $request->validate([
                'tingkat_pelanggaran' => 'required|string',
                'investigator_id' => 'required|exists:users,id',
            ]);

            $kasus->update([
                'status' => 'investigasi',
                'tingkat_pelanggaran' => $request->tingkat_pelanggaran,
                'investigator_id' => $request->investigator_id,
                'catatan_verifikator' => $request->catatan_verifikator
            ]);
            $pesan = 'Laporan berhasil diverifikasi & didisposisikan ke meja Investigator!';
        }

        return redirect()->back()->with('success', $pesan);
    }

    public function editTindakLanjut($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        if (empty($pengaduan->kesimpulan)) {
            return redirect()->route('verifikator.dashboard')->with('error', 'Kasus ini belum memiliki Kertas Kerja / Kesimpulan dari tim Investigator!');
        }

        return view('verifikator.tindaklanjut', compact('pengaduan'));
    }

    public function updateTindakLanjut(Request $request, $id)
    {
        $validatedData = $request->validate([
            'judul_laporan'         => 'sometimes|required|string|max:255',
            'kategori_laporan'      => 'sometimes|required|string',
            'tanggal_kejadian'      => 'sometimes|required|date',
            'lokasi_kejadian'       => 'sometimes|required|string|max:255',
            'isi_laporan'           => 'sometimes|required|string',
            'tingkat_pelanggaran'   => 'nullable|string',
            'investigator_id'       => 'nullable|exists:users,id',
            'fakta_lapangan'        => 'nullable|string',
            'pihak_terlibat'        => 'nullable|string',
            'kesimpulan'            => 'nullable|string',
            'pihak_penindak'        => 'required|string',
            'tanggal_tindak_lanjut' => 'required|date',
            'tindak_lanjut'         => 'required|string',
            'lampiran_bukti'        => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'lampiran_susulan'      => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'bukti_investigasi'     => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'kategori_lainnya'      => 'nullable|string|max:200',
        ]);

        if ($request->kategori_laporan === 'Lainnya' && $request->filled('kategori_lainnya')) {
            if (isset($validatedData['isi_laporan']) && !str_contains($validatedData['isi_laporan'], '⚠️ [SPESIFIKASI KATEGORI]:')) {
                $validatedData['isi_laporan'] = "⚠️ [SPESIFIKASI KATEGORI]: " . strtoupper($request->kategori_lainnya) . "\n--------------------------------------------------\n" . $validatedData['isi_laporan'];
            }
        }

        $kasus = Pengaduan::findOrFail($id);

        if ($request->hasFile('lampiran_bukti')) {
            if ($kasus->lampiran_bukti && Storage::disk('public')->exists($kasus->lampiran_bukti)) {
                Storage::disk('public')->delete($kasus->lampiran_bukti);
            }
            $validatedData['lampiran_bukti'] = $request->file('lampiran_bukti')->store('bukti_pengaduan', 'public');
        } elseif ($request->input('delete_lampiran_bukti') == '1') {
            if ($kasus->lampiran_bukti && Storage::disk('public')->exists($kasus->lampiran_bukti)) {
                Storage::disk('public')->delete($kasus->lampiran_bukti);
            }
            $validatedData['lampiran_bukti'] = null;
        }

        if ($request->hasFile('lampiran_susulan')) {
            if ($kasus->lampiran_susulan && Storage::disk('public')->exists($kasus->lampiran_susulan)) {
                Storage::disk('public')->delete($kasus->lampiran_susulan);
            }
            $validatedData['lampiran_susulan'] = $request->file('lampiran_susulan')->store('bukti_susulan', 'public');
        } elseif ($request->input('delete_lampiran_susulan') == '1') {
            if ($kasus->lampiran_susulan && Storage::disk('public')->exists($kasus->lampiran_susulan)) {
                Storage::disk('public')->delete($kasus->lampiran_susulan);
            }
            $validatedData['lampiran_susulan'] = null;
        }

        if ($request->hasFile('bukti_investigasi')) {
            if ($kasus->bukti_investigasi && Storage::disk('public')->exists($kasus->bukti_investigasi)) {
                Storage::disk('public')->delete($kasus->bukti_investigasi);
            }
            $validatedData['bukti_investigasi'] = $request->file('bukti_investigasi')->store('bukti_investigasi', 'public');
        } elseif ($request->input('delete_bukti_investigasi') == '1') {
            if ($kasus->bukti_investigasi && Storage::disk('public')->exists($kasus->bukti_investigasi)) {
                Storage::disk('public')->delete($kasus->bukti_investigasi);
            }
            $validatedData['bukti_investigasi'] = null;
        }

        $kasus->update(array_merge($validatedData, [
            'status' => 'selesai'
        ]));

        return redirect()->route('verifikator.dashboard')->with('success', 'Data Tindak Lanjut / Keputusan beserta file bukti berhasil disimpan!');
    }
}