<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Tanggapan; 
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    // ==========================================
    // AREA PELAPOR
    // ==========================================

    public function index()
    {
        // Menampilkan Dashboard Pelapor (Riwayat Laporan Sendiri)
        $laporans = Pengaduan::where('user_id', Auth::id())->latest()->get();

        return view('pelapor.dashboard', compact('laporans'));
    }

    // FUNGSI BARU: Menampilkan Halaman Formulir Pengaduan Khusus
    public function create()
    {
        $user = Auth::user();
        return view('pelapor.create', compact('user'));
    }

    public function store(Request $request)
    {
        // Validasi Input
        $request->validate([
            'nama_pelapor'      => 'required',
            'nomor_hp'          => 'required',
            'email'             => 'required|email',
            'judul_laporan'     => 'required',
            'isi_laporan'       => 'required',
            'tanggal_kejadian'  => 'required|date',
            'lokasi_kejadian'   => 'required',
            'kategori_laporan'  => 'required',
            'lampiran_bukti'    => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'kategori_lainnya'  => 'required_if:kategori_laporan,Lainnya|nullable|string|max:200',
        ]);

        $pathBukti = null;
        if ($request->hasFile('lampiran_bukti')) {
            $pathBukti = $request->file('lampiran_bukti')->store('bukti_laporan', 'public');
        }
        $kodeTiket = 'KASUS-' . strtoupper(Str::random(5));

        $isiLaporanFinal = $request->isi_laporan;
        if ($request->kategori_laporan === 'Lainnya' && $request->filled('kategori_lainnya')) {
            $isiLaporanFinal = "⚠️ [SPESIFIKASI KATEGORI]: " . strtoupper($request->kategori_lainnya) . "\n--------------------------------------------------\n" . $request->isi_laporan;
        }

        // Simpan ke Database
        Pengaduan::create([
            'kode_tiket'       => $kodeTiket,
            'user_id'          => Auth::id(), 
            'nama_pelapor'     => $request->nama_pelapor, 
            'nip'              => $request->nip,
            'nomor_hp'         => $request->nomor_hp,
            'email'            => $request->email,
            'judul_laporan'    => $request->judul_laporan,
            'isi_laporan'      => $isiLaporanFinal, 
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'lokasi_kejadian'  => $request->lokasi_kejadian,
            'kategori_laporan' => $request->kategori_laporan,
            'lampiran_bukti'   => $pathBukti,
            'status'           => 'masuk', 
        ]);

        return redirect()->route('pelapor.dashboard')
            ->with('success', 'Laporan berhasil dikirim! Kode Kasus: ' . $kodeTiket);
    
    }

    // =========================================================================
    // AREA INVESTIGATOR (REFACTORED & BULLETPROOF)
    // =========================================================================

    public function indexInvestigator()
    {
        $kasusSaya = Pengaduan::where('status', 'investigasi')
                            ->where('investigator_id', Auth::id())
                            ->whereNull('kesimpulan') 
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('investigator.dashboard', compact('kasusSaya'));
    }

    public function showInvestigator($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);
        
        if ($pengaduan->investigator_id != Auth::id()) {
            abort(403, 'Anda tidak berhak mengakses kasus ini.');
        }

        if (!empty($pengaduan->kesimpulan)) {
            return redirect()->route('investigator.dashboard')
                ->with('success', 'Laporan investigasi kasus ini sudah selesai Anda kerjakan dan telah berada di meja Admin.');
        }

        return view('investigator.detail', compact('pengaduan'));
    }

    public function updateInvestigator(Request $request, $id)
    {
        $request->validate([
            'fakta_lapangan'    => 'required|string',
            'pihak_terlibat'    => 'required|string',
            'kesimpulan'        => 'required|string',
            'bukti_investigasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', 
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        
        $dataUpdate = [
            'status'         => 'selesai', 
            'fakta_lapangan' => $request->fakta_lapangan,
            'pihak_terlibat' => $request->pihak_terlibat,
            'kesimpulan'     => $request->kesimpulan,
        ];

        if ($request->hasFile('bukti_investigasi')) {
            if ($pengaduan->bukti_investigasi && Storage::disk('public')->exists($pengaduan->bukti_investigasi)) {
                Storage::disk('public')->delete($pengaduan->bukti_investigasi);
            }
            $dataUpdate['bukti_investigasi'] = $request->file('bukti_investigasi')->store('bukti_investigasi', 'public');
        }

        $pengaduan->update($dataUpdate);

        return redirect()->route('investigator.dashboard')
                        ->with('success', 'Kasus berhasil diselesaikan! Kertas kerja & bukti telah diarsipkan ke meja Admin.');
    }

    // ==========================================
    // FITUR UMUM (LACAK & LANDING PAGE)
    // ==========================================

    public function formLacak()
    {
        return view('lacak');
    }

    public function cariLacak(Request $request)
    {
        $tiket = $request->kode_tiket;
        $laporan = Pengaduan::where('kode_tiket', $tiket)->first();

        return view('lacak', compact('laporan', 'tiket'));
    }

    public function storeInformasiTambahan(Request $request)
    {
        // 1. Validasi inputan dari form
        $request->validate([
            'kode_tiket' => 'required|exists:pengaduans,kode_tiket',
            'isi_pesan' => 'required|string',
            'lampiran_bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        // 2. Cari data kasusnya
        $pengaduan = Pengaduan::where('kode_tiket', $request->kode_tiket)->firstOrFail();

        // 3. Proses upload file jika pelapor melampirkan file
        $lampiranPath = $pengaduan->lampiran_susulan; // Pertahankan file lama jika ada
        if ($request->hasFile('lampiran_bukti')) {
            $file = $request->file('lampiran_bukti');
            $filename = time() . '_susulan_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/pengaduan'), $filename);
            $lampiranPath = $filename;
        }

        // 4. Update data di tabel pengaduan
        $pengaduan->update([
            'pesan_susulan' => $request->isi_pesan,
            'lampiran_susulan' => $lampiranPath,
        ]);

        return redirect()->back()->with('success', 'Informasi dan bukti tambahan berhasil disimpan ke dalam data kasus.');
    }
}