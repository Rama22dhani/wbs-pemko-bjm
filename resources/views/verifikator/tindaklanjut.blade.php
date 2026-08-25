<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengisian Tindak Lanjut - Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        'bjm-dark': '#0f172a',
                        'bjm-gold': '#d97706',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen font-sans">

    <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-20">
        <div class="flex items-center gap-3">
            <a href="{{ route('verifikator.dashboard') }}" class="text-slate-500 hover:text-bjm-gold flex items-center gap-2 transition font-medium text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Batal & Kembali
            </a>
            <span class="text-slate-300 hidden sm:inline">|</span>
            <span class="font-bold text-lg text-bjm-dark hidden sm:inline">Formulir Keputusan Tindak Lanjut</span>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Formulir Keputusan Akhir Kasus</h1>
            <p class="text-slate-500 mt-1">Tiket: <span class="font-mono font-bold text-bjm-gold">{{ $pengaduan->kode_tiket }}</span> | Pelapor: {{ $pengaduan->nama_pelapor }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1">
                <div class="space-y-6 sticky top-24 max-h-[calc(100vh-6rem)] overflow-y-auto pr-1 pb-4">
                    <!-- KARTU: DATA KASUS -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                        <h3 class="text-sm font-bold text-bjm-dark uppercase tracking-wider mb-4 border-b border-slate-100 pb-3 flex items-center gap-2">
                            <span>📝</span> Data Kasus & Pelapor
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-slate-500 font-bold uppercase mb-1">Pelapor</p>
                                <p class="text-slate-800 font-bold text-sm">{{ $pengaduan->nama_pelapor }}</p>
                                <p class="text-slate-500 text-xs">{{ $pengaduan->nomor_hp }} | {{ $pengaduan->email }}</p>
                                @if($pengaduan->nip)
                                    <p class="text-slate-500 text-xs">NIP: {{ $pengaduan->nip }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-bold uppercase mb-1">Kategori & Tingkat</p>
                                <p class="text-slate-800 font-medium text-sm">{{ $pengaduan->kategori_laporan }}</p>
                                <span class="px-2 py-0.5 mt-1 inline-block text-[10px] rounded font-bold border {{ $pengaduan->tingkat_pelanggaran == 'Berat' ? 'bg-red-50 text-red-700 border-red-200' : ($pengaduan->tingkat_pelanggaran == 'Sedang' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200') }}">
                                    {{ $pengaduan->tingkat_pelanggaran ?? 'Belum Ditentukan' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-bold uppercase mb-1">Isi Laporan</p>
                                <div class="bg-slate-50 p-3 rounded border border-slate-100 text-sm text-slate-700 leading-relaxed max-h-32 overflow-y-auto">
                                    <p class="font-bold mb-1">{{ $pengaduan->judul_laporan }}</p>
                                    {{ $pengaduan->isi_laporan }}
                                </div>
                            </div>
                            
                            @if($pengaduan->pesan_susulan)
                            <div>
                                <p class="text-xs text-cyan-600 font-bold uppercase mb-1">Informasi Tambahan Pelapor</p>
                                <div class="bg-cyan-50 p-3 rounded border border-cyan-100 text-sm text-cyan-800 leading-relaxed font-medium italic">
                                    "{{ $pengaduan->pesan_susulan }}"
                                </div>
                            </div>
                            @endif

                            <div class="flex flex-col gap-2 pt-2 border-t border-slate-100">
                                @if($pengaduan->lampiran_bukti)
                                    <a href="{{ asset('storage/' . $pengaduan->lampiran_bukti) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">📎 Lampiran Bukti Awal</a>
                                @endif
                                @if($pengaduan->lampiran_susulan)
                                    <a href="{{ \Illuminate\Support\Str::startsWith($pengaduan->lampiran_susulan, ['bukti_susulan/', 'bukti_pengaduan/']) ? asset('storage/' . $pengaduan->lampiran_susulan) : asset('uploads/pengaduan/' . $pengaduan->lampiran_susulan) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">📎 Lampiran Bukti Tambahan</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- KARTU: HASIL INVESTIGASI -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                        <h3 class="text-sm font-bold text-bjm-dark uppercase tracking-wider mb-4 border-b border-slate-100 pb-3 flex items-center gap-2">
                            <span>🕵️‍♂️</span> Hasil Investigasi
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-slate-500 font-bold uppercase mb-1">Petugas Lapangan</p>
                                <p class="text-slate-800 font-medium text-sm">{{ $pengaduan->investigator->name ?? 'Tim Lapangan' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-bold uppercase mb-1">Fakta Lapangan</p>
                                <div class="bg-slate-50 p-3 rounded border border-slate-100 text-sm text-slate-700 leading-relaxed max-h-32 overflow-y-auto">
                                    {{ $pengaduan->fakta_lapangan ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-bold uppercase mb-1">Pihak Terkait / Saksi</p>
                                <div class="bg-slate-50 p-3 rounded border border-slate-100 text-sm text-slate-700 leading-relaxed max-h-24 overflow-y-auto">
                                    {{ $pengaduan->pihak_terlibat ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-bold uppercase mb-1">Kesimpulan & Rekomendasi</p>
                                <div class="bg-amber-50 p-3 rounded border border-amber-100 text-sm text-amber-800 leading-relaxed font-medium max-h-32 overflow-y-auto">
                                    {{ $pengaduan->kesimpulan ?? '-' }}
                                </div>
                            </div>
                            
                            @if($pengaduan->bukti_investigasi)
                            <div class="pt-2 border-t border-slate-100">
                                <a href="{{ asset('storage/' . $pengaduan->bukti_investigasi) }}" target="_blank" class="text-xs font-bold text-purple-600 hover:underline flex items-center gap-1">📎 Lampiran Bukti Temuan</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="bg-bjm-dark p-6 border-b-4 border-bjm-gold">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6 text-bjm-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Keputusan & Sanksi Resmi
                        </h2>
                        <p class="text-slate-300 text-sm mt-1">Bagian ini akan dipublikasikan sebagai tindak lanjut resmi dari pemerintah.</p>
                    </div>

                    <form action="{{ route('verifikator.tindaklanjut.update', $pengaduan->id) }}" method="POST" class="p-8">
                        @csrf
                        @method('PUT')

                        <div class="mb-5 mt-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Instansi / Pihak Penindak *</label>
                            <input type="text" name="pihak_penindak" required placeholder="Contoh: Inspektorat Kota Banjarmasin" 
                                class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-dark focus:ring-1 focus:ring-bjm-dark outline-none transition">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Eksekusi Sanksi *</label>
                            <input type="date" name="tanggal_tindak_lanjut" required 
                                class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-dark focus:ring-1 focus:ring-bjm-dark outline-none transition">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tindak Lanjut / Sanksi yang Diberikan <span class="text-red-500">*</span></label>
                            <textarea name="tindak_lanjut" rows="6" required 
                                class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl p-4 focus:border-bjm-gold focus:ring-2 focus:ring-amber-200 outline-none transition leading-relaxed" 
                                placeholder="Contoh: Berdasarkan hasil pemeriksaan, pihak yang bersangkutan telah diberikan sanksi teguran keras dan pengembalian dana...">{{ $pengaduan->tindak_lanjut }}</textarea>
                            <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Pastikan redaksi bahasa sudah tepat dan formal sebelum disimpan.
                            </p>
                        </div>

                        <div class="flex items-center gap-4 border-t border-slate-200 pt-6">
                            <button type="submit" onclick="return confirm('Simpan keputusan tindak lanjut ini?')" 
                                class="bg-bjm-dark hover:bg-slate-800 text-white font-bold py-3 px-8 rounded-lg shadow-md transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                SIMPAN TINDAK LANJUT
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>