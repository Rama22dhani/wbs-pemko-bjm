<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PengaduanExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function index()
    {
        // 1. DATA AKSES (Sebelumnya bernama Data Pegawai)
        $dataPegawai = User::whereIn('peran', ['admin', 'investigator'])->latest()->get();

        // 1-B. MASTER DATA PEGAWAI (Profil Fisik Kepegawaian Baru)
        $dataMasterPegawai = \App\Models\Pegawai::with('user')->latest()->get();

        // 2. DATA PELAPOR
        $dataPengguna = User::where('peran', 'pelapor')->latest()->get();

        // 3. DATA KASUS (Hanya melihat yang sudah diverifikasi)
        $dataKasus = Pengaduan::where('status', '!=', 'masuk')->latest()->get();

        // 4. DATA TINGKAT PELANGGARAN (Diambil dari tabel pengaduan)
        $dataPelanggaran = Pengaduan::whereNotNull('tingkat_pelanggaran')->latest()->get();

        // 5. DATA BUKTI
        $dataBukti = Pengaduan::where(function($q) {
                                $q->whereNotNull('lampiran_bukti')
                                ->orWhereNotNull('lampiran_susulan')
                                ->orWhereNotNull('bukti_investigasi');
                            })
                            ->latest()
                            ->get();

        // 6. DATA INVESTIGASI
        $dataInvestigasi = Pengaduan::where(function($q) {
                                        $q->whereNotNull('fakta_lapangan')
                                        ->orWhereNotNull('hasil_investigasi');
                                    })
                                    ->latest()
                                    ->get();

        // 7. ANTREAN INPUT TINDAK LANJUT
        $kasusPerluTindakLanjut = Pengaduan::whereNotNull('kesimpulan')
                                        ->whereNull('tindak_lanjut')
                                        ->latest()
                                        ->get();

        // 8. DATA TINDAK LANJUT
        $dataTindakLanjut = Pengaduan::whereNotNull('tindak_lanjut')
                                    ->latest()
                                    ->get();

        // 9. DATA INFORMASI TAMBAHAN (Revisi Pengganti Tanggapan)
        $dataInfoTambahan = Pengaduan::whereNotNull('pesan_susulan')
                                    ->latest()
                                    ->get();

        return view('admin.dashboard', compact(
            'dataPegawai', 
            'dataMasterPegawai', 
            'dataPengguna', 
            'dataKasus', 
            'dataPelanggaran', 
            'dataBukti', 
            'dataInvestigasi',
            'kasusPerluTindakLanjut',
            'dataTindakLanjut',
            'dataInfoTambahan'
        ));
    }

    // FUNGSI MENAMPILKAN BERKAS KASUS (DETAIL ADMIN)
    public function show($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);
        return view('admin.detail', compact('pengaduan'));
    }

    // FUNGSI HALAMAN INPUT TINDAK LANJUT
    public function editTindakLanjut($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        if (empty($pengaduan->kesimpulan)) {
            return redirect()->route('admin.dashboard')->with('error', 'Kasus ini belum memiliki Kertas Kerja / Kesimpulan dari tim Investigator!');
        }

        return view('admin.tindaklanjut', compact('pengaduan'));
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

        return redirect()->route('admin.dashboard')->with('success', 'Data Tindak Lanjut / Keputusan beserta file bukti berhasil disimpan!');
    }

    // CRUD MASTER DATA PEGAWAI
    public function storePegawai(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'peran' => 'required|in:admin,investigator',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'peran' => $request->peran,
        ]);

        return redirect()->back()->with('success', 'Data Pegawai Berhasil Ditambahkan');
    }

    public function updatePegawai(Request $request, $id)
    {
        $pegawai = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$pegawai->id,
            'peran' => 'required|in:admin,investigator',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'peran' => $request->peran,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $pegawai->update($data);

        return redirect()->back()->with('success', 'Data Pegawai berhasil diperbarui!');
    }

    public function destroyPegawai($id)
    {
        if (Auth::id() == $id) {
            return redirect()->back()->with('error', 'Peringatan: Anda tidak bisa menghapus akun Anda sendiri!');
        }

        $pegawai = User::findOrFail($id);
        $pegawai->delete();
        
        return redirect()->back()->with('success', 'Data Pegawai berhasil dihapus!');
    }

    // CRUD MASTER DATA PELAPOR
    public function storePengguna(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'peran' => 'pelapor', 
        ]);

        return redirect()->back()->with('success', 'Data Masyarakat berhasil ditambahkan!');
    }

    public function updatePengguna(Request $request, $id)
    {
        $pengguna = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$pengguna->id,
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $pengguna->update($data);

        return redirect()->back()->with('success', 'Data Masyarakat berhasil diperbarui!');
    }

    public function destroyPengguna($id)
    {
        $pengguna = User::findOrFail($id);
        $pengguna->delete();
        return redirect()->back()->with('success', 'Data Masyarakat berhasil dihapus!');
    }

    public function updateKasus(Request $request, $id)
    {
        $kasus = Pengaduan::findOrFail($id);

        $request->validate([
            'kode_tiket'            => 'required|string|max:255|unique:pengaduans,kode_tiket,' . $kasus->id,
            'user_id'               => 'nullable|exists:users,id',
            'nama_pelapor'          => 'nullable|string|max:255',
            'nip'                   => 'nullable|string|max:255',
            'nomor_hp'              => 'nullable|string|max:255',
            'email'                 => 'nullable|email|max:255',
            'judul_laporan'         => 'required|string|max:255',
            'kategori_laporan'      => 'required|string',
            'tanggal_kejadian'      => 'required|date',
            'lokasi_kejadian'       => 'required|string|max:255',
            'isi_laporan'           => 'required|string',
            'status'                => 'required|string|in:masuk,verifikasi,investigasi,selesai,ditolak',
            'tingkat_pelanggaran'   => 'nullable|string|in:Ringan,Sedang,Berat',
            'catatan_verifikator'   => 'nullable|string',
            'alasan_penolakan'      => 'nullable|string',
            'investigator_id'       => 'nullable|exists:users,id',
            'pesan_susulan'         => 'nullable|string',
            'hasil_investigasi'     => 'nullable|string',
            'fakta_lapangan'        => 'nullable|string',
            'pihak_terlibat'        => 'nullable|string',
            'kesimpulan'            => 'nullable|string',
            'tindak_lanjut'         => 'nullable|string',
            'pihak_penindak'        => 'nullable|string',
            'tanggal_tindak_lanjut' => 'nullable|date',
            'lampiran_bukti'        => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'lampiran_susulan'      => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'bukti_investigasi'     => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'kategori_lainnya'      => 'nullable|string|max:200',
        ]);

        $data = [
            'kode_tiket'            => $request->kode_tiket,
            'user_id'               => $request->user_id,
            'nama_pelapor'          => $request->nama_pelapor,
            'nip'                   => $request->nip,
            'nomor_hp'              => $request->nomor_hp,
            'email'                 => $request->email,
            'judul_laporan'         => $request->judul_laporan,
            'kategori_laporan'      => $request->kategori_laporan,
            'tanggal_kejadian'      => $request->tanggal_kejadian,
            'lokasi_kejadian'       => $request->lokasi_kejadian,
            'isi_laporan'           => ($request->kategori_laporan === 'Lainnya' && $request->filled('kategori_lainnya') && !str_contains($request->isi_laporan, '⚠️ [SPESIFIKASI KATEGORI]:')) ? "⚠️ [SPESIFIKASI KATEGORI]: " . strtoupper($request->kategori_lainnya) . "\n--------------------------------------------------\n" . $request->isi_laporan : $request->isi_laporan,
            'status'                => $request->status,
            'tingkat_pelanggaran'   => $request->tingkat_pelanggaran,
            'catatan_verifikator'   => $request->catatan_verifikator,
            'alasan_penolakan'      => $request->alasan_penolakan,
            'investigator_id'       => $request->investigator_id,
            'pesan_susulan'         => $request->pesan_susulan,
            'hasil_investigasi'     => $request->hasil_investigasi,
            'fakta_lapangan'        => $request->fakta_lapangan,
            'pihak_terlibat'        => $request->pihak_terlibat,
            'kesimpulan'            => $request->kesimpulan,
            'tindak_lanjut'         => $request->tindak_lanjut,
            'pihak_penindak'        => $request->pihak_penindak,
            'tanggal_tindak_lanjut' => $request->tanggal_tindak_lanjut,
        ];

        // Handle file uploads
        if ($request->hasFile('lampiran_bukti')) {
            if ($kasus->lampiran_bukti && Storage::disk('public')->exists($kasus->lampiran_bukti)) {
                Storage::disk('public')->delete($kasus->lampiran_bukti);
            }
            $data['lampiran_bukti'] = $request->file('lampiran_bukti')->store('bukti_pengaduan', 'public');
        } elseif ($request->input('delete_lampiran_bukti') == '1') {
            if ($kasus->lampiran_bukti && Storage::disk('public')->exists($kasus->lampiran_bukti)) {
                Storage::disk('public')->delete($kasus->lampiran_bukti);
            }
            $data['lampiran_bukti'] = null;
        }

        if ($request->hasFile('lampiran_susulan')) {
            if ($kasus->lampiran_susulan && Storage::disk('public')->exists($kasus->lampiran_susulan)) {
                Storage::disk('public')->delete($kasus->lampiran_susulan);
            }
            $data['lampiran_susulan'] = $request->file('lampiran_susulan')->store('bukti_susulan', 'public');
        } elseif ($request->input('delete_lampiran_susulan') == '1') {
            if ($kasus->lampiran_susulan && Storage::disk('public')->exists($kasus->lampiran_susulan)) {
                Storage::disk('public')->delete($kasus->lampiran_susulan);
            }
            $data['lampiran_susulan'] = null;
        }

        if ($request->hasFile('bukti_investigasi')) {
            if ($kasus->bukti_investigasi && Storage::disk('public')->exists($kasus->bukti_investigasi)) {
                Storage::disk('public')->delete($kasus->bukti_investigasi);
            }
            $data['bukti_investigasi'] = $request->file('bukti_investigasi')->store('bukti_investigasi', 'public');
        } elseif ($request->input('delete_bukti_investigasi') == '1') {
            if ($kasus->bukti_investigasi && Storage::disk('public')->exists($kasus->bukti_investigasi)) {
                Storage::disk('public')->delete($kasus->bukti_investigasi);
            }
            $data['bukti_investigasi'] = null;
        }

        $kasus->update($data);

        return redirect()->back()->with('success', 'Seluruh data kasus dan lampiran berhasil diperbarui!');
    }

    public function destroyKasus($id)
    {
        $kasus = Pengaduan::findOrFail($id);
        if ($kasus->lampiran_bukti && Storage::disk('public')->exists($kasus->lampiran_bukti)) {
            Storage::disk('public')->delete($kasus->lampiran_bukti);
        }
        if ($kasus->bukti_investigasi && Storage::disk('public')->exists($kasus->bukti_investigasi)) {
            Storage::disk('public')->delete($kasus->bukti_investigasi);
        }
        if ($kasus->lampiran_susulan && Storage::disk('public')->exists($kasus->lampiran_susulan)) {
            Storage::disk('public')->delete($kasus->lampiran_susulan);
        }
        $kasus->delete();

        return redirect()->back()->with('success', 'Data Kasus beserta seluruh buktinya berhasil dihapus permanen!');
    }

    public function updatePelanggaran(Request $request, $id)
    {
        $request->validate([
            'tingkat_pelanggaran' => 'nullable|string',
            'investigator_id' => 'nullable|exists:users,id',
            'catatan_verifikator' => 'nullable|string',
        ]);

        $kasus = Pengaduan::findOrFail($id);
        $kasus->update([
            'tingkat_pelanggaran' => $request->tingkat_pelanggaran,
            'investigator_id' => $request->investigator_id,
            'catatan_verifikator' => $request->catatan_verifikator,
        ]);

        return redirect()->back()->with('success', 'Data Verifikasi Pelanggaran berhasil diperbarui!');
    }

    public function destroyPelanggaran($id)
    {
        $kasus = Pengaduan::findOrFail($id);
        $kasus->update([
            'tingkat_pelanggaran' => null,
            'investigator_id'     => null,
            'catatan_verifikator' => null,
            'status'              => 'masuk', 
        ]);

        return redirect()->back()->with('success', 'Data Verifikasi berhasil direset!');
    }

    public function updateInvestigasi(Request $request, $id)
    {
        $request->validate([
            'fakta_lapangan'    => 'required|string',
            'pihak_terlibat'    => 'required|string',
            'kesimpulan'        => 'required|string',
            'investigator_id'   => 'required|exists:users,id',
            'bukti_investigasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $kasus = Pengaduan::findOrFail($id);
        
        $dataUpdate = [
            'fakta_lapangan'  => $request->fakta_lapangan,
            'pihak_terlibat'  => $request->pihak_terlibat,
            'kesimpulan'      => $request->kesimpulan,
            'investigator_id' => $request->investigator_id,
        ];

        if ($request->hasFile('bukti_investigasi')) {
            if ($kasus->bukti_investigasi && Storage::disk('public')->exists($kasus->bukti_investigasi)) {
                Storage::disk('public')->delete($kasus->bukti_investigasi);
            }
            $dataUpdate['bukti_investigasi'] = $request->file('bukti_investigasi')->store('bukti_investigasi', 'public');
        } elseif ($request->input('delete_bukti_investigasi') == '1') {
            if ($kasus->bukti_investigasi && Storage::disk('public')->exists($kasus->bukti_investigasi)) {
                Storage::disk('public')->delete($kasus->bukti_investigasi);
            }
            $dataUpdate['bukti_investigasi'] = null;
        }

        $kasus->update($dataUpdate);

        return redirect()->back()->with('success', 'Kertas kerja investigasi berhasil dikoreksi!');
    }

    public function destroyInvestigasi($id)
    {
        $kasus = Pengaduan::findOrFail($id);
        if ($kasus->bukti_investigasi && Storage::disk('public')->exists($kasus->bukti_investigasi)) {
            Storage::disk('public')->delete($kasus->bukti_investigasi);
        }

        $kasus->update([
            'fakta_lapangan' => null,
            'pihak_terlibat' => null,
            'kesimpulan' => null,
            'bukti_investigasi' => null,
            'status' => 'investigasi', 
        ]);

        return redirect()->back()->with('success', 'Data investigasi berhasil direset. Status kasus dikembalikan menjadi Proses Investigasi.');
    }

    public function destroyTindakLanjut($id)
    {
        $kasus = Pengaduan::findOrFail($id);
        
        $kasus->update([
            'pihak_penindak'        => null,
            'tanggal_tindak_lanjut' => null,
            'tindak_lanjut'         => null,
            'status'                => 'investigasi', // Lebih aman dikembalikan ke investigasi
        ]);

        return redirect()->back()->with('success', 'Keputusan Tindak Lanjut berhasil dibatalkan. Data dikembalikan ke tabel Antrean Input Tindak Lanjut.');
    }

    public function updateBukti(Request $request, $id)
    {
        $request->validate([
            'lampiran_bukti'    => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'lampiran_susulan'  => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', 
            'bukti_investigasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        $kasus = \App\Models\Pengaduan::findOrFail($id);

        // 1. Update Bukti Awal Pelapor
        if ($request->hasFile('lampiran_bukti')) {
            if ($kasus->lampiran_bukti && Storage::disk('public')->exists($kasus->lampiran_bukti)) {
                Storage::disk('public')->delete($kasus->lampiran_bukti);
            }
            $kasus->lampiran_bukti = $request->file('lampiran_bukti')->store('bukti_pengaduan', 'public');
        } elseif ($request->input('delete_lampiran_bukti') == '1') {
            if ($kasus->lampiran_bukti && Storage::disk('public')->exists($kasus->lampiran_bukti)) {
                Storage::disk('public')->delete($kasus->lampiran_bukti);
            }
            $kasus->lampiran_bukti = null;
        }

        // 2. Update Bukti Tambahan (Revisi Baru)
        if ($request->hasFile('lampiran_susulan')) {
            if ($kasus->lampiran_susulan && Storage::disk('public')->exists($kasus->lampiran_susulan)) {
                Storage::disk('public')->delete($kasus->lampiran_susulan);
            }
            $kasus->lampiran_susulan = $request->file('lampiran_susulan')->store('bukti_susulan', 'public');
        } elseif ($request->input('delete_lampiran_susulan') == '1') {
            if ($kasus->lampiran_susulan) {
                $publicPath = public_path('uploads/pengaduan/' . $kasus->lampiran_susulan);
                if (file_exists($publicPath)) {
                    @unlink($publicPath);
                }
                if (Storage::disk('public')->exists($kasus->lampiran_susulan)) {
                    Storage::disk('public')->delete($kasus->lampiran_susulan);
                }
            }
            $kasus->lampiran_susulan = null;
        }

        // 3. Update Bukti Investigasi
        if ($request->hasFile('bukti_investigasi')) {
            if ($kasus->bukti_investigasi && Storage::disk('public')->exists($kasus->bukti_investigasi)) {
                Storage::disk('public')->delete($kasus->bukti_investigasi);
            }
            $kasus->bukti_investigasi = $request->file('bukti_investigasi')->store('bukti_investigasi', 'public');
        } elseif ($request->input('delete_bukti_investigasi') == '1') {
            if ($kasus->bukti_investigasi && Storage::disk('public')->exists($kasus->bukti_investigasi)) {
                Storage::disk('public')->delete($kasus->bukti_investigasi);
            }
            $kasus->bukti_investigasi = null;
        }

        $kasus->save();

        return redirect()->back()->with('success', 'File bukti berhasil diperbarui!');
    }

    public function destroyBukti($id)
    {
        $kasus = Pengaduan::findOrFail($id);
        
        // 1. Hapus Lampiran Bukti Awal
        if ($kasus->lampiran_bukti && Storage::disk('public')->exists($kasus->lampiran_bukti)) {
            Storage::disk('public')->delete($kasus->lampiran_bukti);
        }
        
        // 2. Hapus Lampiran Bukti Tambahan/Susulan (dari Storage atau public_path)
        if ($kasus->lampiran_susulan) {
            $publicPath = public_path('uploads/pengaduan/' . $kasus->lampiran_susulan);
            if (file_exists($publicPath)) {
                @unlink($publicPath);
            }
            if (Storage::disk('public')->exists($kasus->lampiran_susulan)) {
                Storage::disk('public')->delete($kasus->lampiran_susulan);
            }
        }
        
        // 3. Hapus Bukti Investigasi Lapangan
        if ($kasus->bukti_investigasi && Storage::disk('public')->exists($kasus->bukti_investigasi)) {
            Storage::disk('public')->delete($kasus->bukti_investigasi);
        }

        $kasus->update([
            'lampiran_bukti'    => null,
            'lampiran_susulan'  => null,
            'bukti_investigasi' => null
        ]);

        return redirect()->back()->with('success', 'Seluruh file bukti pada kasus ini berhasil dihapus permanen!');
    }

    // REVISI: Fungsi Tanggapan diubah menjadi Informasi Tambahan
    public function updateInfoTambahan(Request $request, $id)
    {
        $validatedData = $request->validate([
            'pesan_susulan' => 'required|string',
        ]);

        $kasus = Pengaduan::findOrFail($id);
        $kasus->update($validatedData);

        return redirect()->back()->with('success', 'Informasi tambahan berhasil diperbarui!');
    }

    public function destroyInfoTambahan($id)
    {
        $kasus = Pengaduan::findOrFail($id);
        
        if ($kasus->lampiran_susulan && Storage::disk('public')->exists($kasus->lampiran_susulan)) {
            Storage::disk('public')->delete($kasus->lampiran_susulan);
        }

        $kasus->update([
            'pesan_susulan' => null,
            'lampiran_susulan' => null,
        ]);

        return redirect()->back()->with('success', 'Informasi tambahan beserta lampirannya berhasil dihapus permanen!');
    }

    // FUNGSI CETAK PDF DETAIL KASUS
    public function cetakPdf($id)
    {
        $pengaduan = Pengaduan::with(['user', 'user.pegawai', 'investigator', 'investigator.pegawai'])->findOrFail($id);
        $pdf = Pdf::loadView('admin.pdf_detail', compact('pengaduan'));

        return $pdf->stream('Laporan_SAPA_PEMKO_'. $pengaduan->kode_tiket . '.pdf', ['Attachment' => false]);
    }

    // FUNGSI CETAK REKAPITULASI
    public function cetakRekap($kategori)
    {
        $title = "";
        $data = [];

        switch ($kategori) {
            case 'kasus':
                $title = "LAPORAN REKAPITULASI DATA KASUS";
                $data = Pengaduan::with('user')->latest()->get();
                break;

            case 'investigasi':
                $title = "LAPORAN REKAPITULASI DATA HASIL INVESTIGASI";
                $data = Pengaduan::whereNotNull('fakta_lapangan')->latest()->get();
                break;
            case 'tindaklanjut':
                $title = "LAPORAN REKAPITULASI DATA TINDAK LANJUT";
                $data = Pengaduan::whereNotNull('tindak_lanjut')->latest()->get();
                break;
            case 'bukti':
                $title = "LAPORAN REKAPITULASI ARSIP LAMPIRAN BUKTI";
                $data = Pengaduan::where(function($q) {
                    $q->whereNotNull('lampiran_bukti')
                      ->orWhereNotNull('lampiran_susulan')
                      ->orWhereNotNull('bukti_investigasi');
                })->latest()->get();
                break;
            case 'tanggapan':
                // REVISI: Mengambil data dari tabel pengaduan yang ada pesan susulannya
                $title = "LAPORAN REKAPITULASI DATA INFORMASI TAMBAHAN PELAPOR";
                $data = Pengaduan::whereNotNull('pesan_susulan')->with('user')->latest()->get();
                break;
            case 'pegawai':
                $title = "LAPORAN REKAPITULASI DATA AKSES PENGAWAS (AKUN)";
                $data = User::whereIn('peran', ['admin', 'investigator'])->latest()->get();
                break;
            case 'pengguna':
                $title = "LAPORAN REKAPITULASI DATA PELAPOR";
                $data = User::where('peran', 'pelapor')->orWhereNull('peran')->latest()->get();
                break;
            case 'master_pegawai':
                $title = "LAPORAN REKAPITULASI MASTER DATA PEGAWAI";
                $data = Pegawai::with('user')->latest()->get();
                break;

            default:
                return redirect()->back()->with('error', 'Kategori rekapitulasi tidak valid!');
        }

        $pdf = Pdf::loadView('admin.pdf_rekap', compact('data', 'title', 'kategori'));
        
        return $pdf->stream('Rekap_' . $kategori . '_' . date('Ymd') . '.pdf', ['Attachment' => false]);
    }

    public function exportExcel()
    {
        return Excel::download(new PengaduanExport, 'Data-Pengaduan-Pemko-' . date('Y-m-d') . '.xlsx');
    }

    // FUNGSI KHUSUS: ADMIN EKSEKUSI VERIFIKASI & DISPOSISI KASUS
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

    // CRUD MASTER DATA PEGAWAI (PROFIL FISIK)
    public function storeMasterPegawai(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nip' => 'required|string|unique:pegawais,nip',
            'nama_pegawai' => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'status_kepegawaian' => 'required|in:PNS,PPPK,CPNS,Honorer',
            'asal_instansi' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nomor_hp' => 'nullable|string|max:20',
            'status_aktif' => 'required|in:Aktif,Nonaktif',
        ]);

        Pegawai::create($validated);
        return redirect()->back()->with('success', 'Master Data Pegawai berhasil ditambahkan!');
    }

    public function updateMasterPegawai(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nip' => 'required|string|unique:pegawais,nip,'.$id,
            'nama_pegawai' => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'status_kepegawaian' => 'required|in:PNS,PPPK,CPNS,Honorer',
            'asal_instansi' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nomor_hp' => 'nullable|string|max:20',
            'status_aktif' => 'required|in:Aktif,Nonaktif',
        ]);

        $pegawai->update($validated);
        return redirect()->back()->with('success', 'Master Data Pegawai berhasil diperbarui!');
    }

    public function destroyMasterPegawai($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();
        return redirect()->back()->with('success', 'Master Data Pegawai berhasil dihapus!');
    }
}