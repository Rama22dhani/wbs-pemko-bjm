<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proses Investigasi - Sistem Pelaporan Pelanggaran</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bjm-blue': '#0f172a',
                        'bjm-surface': '#1e293b',
                        'bjm-gold': '#d97706',
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen font-sans">

    <nav class="bg-bjm-blue border-b-4 border-bjm-gold sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <a href="{{ route('investigator.dashboard') }}" class="text-white/70 hover:text-white flex items-center gap-2 transition font-medium text-sm bg-white/10 px-3 py-1.5 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                    <span class="text-slate-500 mx-2">|</span>
                    <span class="font-extrabold text-lg text-white tracking-wide">Investigasi <span class="text-bjm-gold">#{{ $pengaduan->kode_tiket }}</span></span>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- SIDEBAR KIRI: BERKAS LAPORAN -->
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-gradient-to-b from-white to-slate-50 border border-slate-200 rounded-2xl p-6 shadow-md relative overflow-hidden">
                    <h3 class="text-sm font-black text-bjm-blue uppercase tracking-wider mb-4 border-b border-slate-200 pb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-bjm-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Berkas Laporan
                    </h3>
                    
                    <div class="space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Pelapor</p>
                                <p class="text-slate-800 font-bold text-sm">{{ $pengaduan->nama_pelapor }}</p>
                                <p class="text-slate-500 text-[10px] font-medium mt-0.5">{{ $pengaduan->nomor_hp }} | {{ $pengaduan->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Kategori</p>
                                <p class="text-slate-800 font-bold text-sm">{{ $pengaduan->kategori_laporan }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Tgl Kejadian</p>
                                <p class="text-slate-600 font-medium text-sm">{{ \Carbon\Carbon::parse($pengaduan->tanggal_kejadian)->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Tingkat</p>
                                <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md border shadow-sm inline-block
                                    {{ $pengaduan->tingkat_pelanggaran == 'Berat' ? 'bg-red-50 text-red-600 border-red-200' : ($pengaduan->tingkat_pelanggaran == 'Sedang' ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-emerald-50 text-emerald-600 border-emerald-200') }}">
                                    {{ $pengaduan->tingkat_pelanggaran ?? 'Umum' }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase mb-1">Judul Laporan</p>
                            <p class="text-slate-800 font-bold">{{ $pengaduan->judul_laporan }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase mb-1">Lokasi Kejadian</p>
                            <p class="text-slate-600 text-sm font-medium">{{ $pengaduan->lokasi_kejadian }}</p>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-200">
                            <p class="text-xs font-bold text-slate-400 uppercase mb-2">Kronologi / Isi Laporan</p>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 max-h-48 overflow-y-auto shadow-inner">
                                <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $pengaduan->isi_laporan }}</p>
                            </div>
                        </div>

                        @if($pengaduan->lampiran_bukti)
                        <div class="pt-4 border-t border-slate-200">
                            <a href="{{ asset('storage/' . $pengaduan->lampiran_bukti) }}" target="_blank" class="flex items-center justify-center gap-2 w-full text-center bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white py-2.5 rounded-xl text-sm transition border border-blue-200 shadow-sm font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                Lihat Bukti Pelapor
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                @if($pengaduan->catatan_verifikator)
                <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-2xl p-6 shadow-sm border-l-4 border-l-amber-500">
                    <h3 class="text-sm font-black text-amber-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Instruksi Verifikasi Admin
                    </h3>
                    <p class="text-amber-800 text-sm font-medium leading-relaxed">
                        "{{ $pengaduan->catatan_verifikator }}"
                    </p>
                </div>
                @endif

                <!-- INFORMASI TAMBAHAN PELAPOR -->
                @if($pengaduan->pesan_susulan)
                <div class="bg-gradient-to-b from-white to-slate-50 border border-slate-200 rounded-2xl overflow-hidden shadow-md">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-6 border-b border-slate-200 pb-4">
                            <div class="bg-cyan-600 p-2 rounded-lg text-white shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-800">Informasi Tambahan</h3>
                                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Keterangan susulan pelapor.</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-cyan-50/50 border border-cyan-100 p-4 rounded-xl flex gap-3 shadow-sm">
                                <div class="flex-1">
                                    <p class="text-xs text-slate-500 font-bold uppercase mb-2">Pesan Susulan</p>
                                    <div class="bg-white p-3 rounded-lg border border-cyan-100 text-slate-700 leading-relaxed text-xs font-medium italic">
                                        "{{ $pengaduan->pesan_susulan }}"
                                    </div>
                                    @if($pengaduan->lampiran_susulan)
                                    <div class="mt-3 pt-3 border-t border-slate-200">
                                        @php
                                            $isStorageFile = \Illuminate\Support\Str::startsWith($pengaduan->lampiran_susulan, ['bukti_susulan/', 'bukti_pengaduan/']);
                                            $filePath = $isStorageFile ? asset('storage/' . $pengaduan->lampiran_susulan) : asset('uploads/pengaduan/' . $pengaduan->lampiran_susulan);
                                        @endphp
                                        <a href="{{ $filePath }}" target="_blank" class="inline-flex items-center gap-1.5 bg-cyan-50 hover:bg-cyan-600 border border-cyan-200 text-cyan-700 hover:text-white font-bold px-3 py-1.5 rounded-lg text-[10px] transition shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            Lihat Lampiran Bukti Tambahan
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- KANAN: KERTAS KERJA INVESTIGATOR -->
            <div class="lg:col-span-2">
                <div class="bg-gradient-to-b from-white to-slate-50 border border-slate-200 rounded-2xl p-8 shadow-md">
                    
                    <h3 class="text-2xl font-black text-bjm-blue mb-6 flex items-center gap-3 border-b border-slate-200 pb-5">
                        <div class="bg-bjm-gold p-2 rounded-lg text-white shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        Kertas Kerja Investigator
                    </h3>

                    <form action="{{ route('investigator.update', $pengaduan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6 mb-8">
                            <!-- KOLOM 1 -->
                            <div>
                                <label class="block text-sm font-extrabold mb-1 text-slate-700">1. Fakta Objektif yang Ditemukan di Lapangan</label>
                                <p class="text-[11px] text-slate-500 mb-2 italic">Uraikan kondisi nyata di lokasi. Jika tidak ada bukti pelanggaran, sebutkan secara jujur.</p>
                                <textarea name="fakta_lapangan" rows="4" required 
                                    class="w-full bg-white border border-slate-300 text-slate-800 rounded-xl p-4 focus:border-bjm-gold focus:ring-1 focus:ring-bjm-gold outline-none leading-relaxed shadow-sm transition text-sm" 
                                    placeholder="Contoh Terbukti: Ditemukan 2 unit laptop dinas di rumah terlapor...&#10;Contoh Tidak Terbukti: Hasil sidak gudang hari Sabtu jam 10 pagi, seluruh 50 unit komputer komplet bersegel resmi. Tidak ada aset yang hilang.">{{ $pengaduan->fakta_lapangan ?? '' }}</textarea>
                            </div>
                            
                            <!-- KOLOM 2 -->
                            <div>
                                <label class="block text-sm font-extrabold mb-1 text-slate-700">2. Pihak Terkait yang Dimintai Keterangan</label>
                                <p class="text-[11px] text-slate-500 mb-2 italic">Sebutkan saksi/terlapor beserta esensi keterangan mereka.</p>
                                <textarea name="pihak_terlibat" rows="3" required 
                                    class="w-full bg-white border border-slate-300 text-slate-800 rounded-xl p-4 focus:border-bjm-gold focus:ring-1 focus:ring-bjm-gold outline-none leading-relaxed shadow-sm transition text-sm" 
                                    placeholder="Contoh: Bapak Hendra (Kepala Gudang) menyatakan bahwa seluruh barang baru disortir dan tidak pernah dikeluarkan tanpa surat izin...">{{ $pengaduan->pihak_terlibat ?? '' }}</textarea>
                            </div>

                            <!-- KOLOM 3 -->
                            <div>
                                <label class="block text-sm font-extrabold mb-1 text-slate-700">3. Kesimpulan Materiil & Rekomendasi</label>
                                <p class="text-[11px] text-slate-500 mb-2 italic">Tuliskan putusan investigasi Anda secara tegas agar Admin mudah mengambil keputusan.</p>
                                <textarea name="kesimpulan" rows="4" required 
                                    class="w-full bg-white border border-slate-300 text-slate-800 rounded-xl p-4 focus:border-bjm-gold focus:ring-1 focus:ring-bjm-gold outline-none leading-relaxed shadow-sm transition text-sm font-medium" 
                                    placeholder="Jika Terbukti: TERBUKTI MELANGGAR. Direkomendasikan pemanggilan terlapor ke Inspektorat...&#10;&#10;Jika Tidak Terbukti: TIDAK TERBUKTI. Dugaan pelapor tidak akurat / salah identifikasi aset. Direkomendasikan penutupan laporan tanpa sanksi.">{{ $pengaduan->kesimpulan ?? '' }}</textarea>
                            </div>

                            <!-- KOLOM 4: BUKTI TEMUAN DENGAN PREVIEW AKTIF -->
                            <div class="pt-6 border-t border-slate-200">
                                <label class="block text-sm font-extrabold mb-1 text-slate-700">4. Lampiran Bukti Temuan Lapangan (Opsional)</label>
                                <p class="text-[11px] text-slate-500 mb-3">Unggah foto kondisi lapangan atau dokumen klarifikasi dari terlapor.</p>
                                
                                <div class="flex items-center justify-center w-full">
                                    <label id="dropzone-label" for="dropzone-file" class="flex flex-col items-center justify-center w-full h-36 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 hover:border-bjm-gold transition shadow-inner relative overflow-hidden">
                                        
                                        <!-- Tampilan Default (Belum Upload) -->
                                        <div id="default-dropzone-content" class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                            <svg class="w-10 h-10 mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                            <p class="mb-1 text-sm text-slate-600"><span class="font-bold text-bjm-gold">Klik untuk unggah</span> foto atau berkas temuan</p>
                                            <p class="text-xs text-slate-400 font-medium">Mendukung: PDF, JPG, PNG (Maks. 2MB)</p>
                                        </div>

                                        <!-- Tampilan Setelah File Terpilih (Awalnya Sembunyi) -->
                                        <div id="selected-dropzone-content" class="hidden flex flex-col items-center justify-center w-full h-full bg-emerald-50 text-center px-4">
                                            <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center mb-2 shadow-md">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <p class="text-sm font-bold text-emerald-900 truncate max-w-md" id="display-file-name">Nama_File.jpg</p>
                                            <p class="text-[11px] font-semibold text-emerald-600 mt-0.5">✔ File siap diunggah saat Anda menyimpan laporan</p>
                                        </div>

                                        <input id="dropzone-file" type="file" name="bukti_investigasi" class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" />
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl mb-6 shadow-sm">
                            <p class="text-xs text-amber-800 font-medium flex items-start gap-2 leading-relaxed">
                                <svg class="w-5 h-5 flex-shrink-0 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Pastikan seluruh Kertas Kerja Investigator telah diisi objektif. Setelah Anda menekan tombol di bawah, kasus ini akan diteruskan kembali ke Admin untuk penjatuhan keputusan/sanksi final atau penutupan kasus.
                            </p>
                        </div>

                        <div class="border-t border-slate-200 pt-6">
                            <button type="submit" onclick="return confirm('Tutup tahap investigasi lapangan? Pastikan Kertas Kerja sudah terisi lengkap.')" 
                                class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-600/30 transition transform hover:-translate-y-1 flex justify-center items-center gap-2 text-lg tracking-wide">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                SELESAIKAN INVESTIGASI & SIMPAN LAPORAN
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

    <!-- SCRIPT PREVIEW FILE INPUT -->
    <script>
        document.getElementById('dropzone-file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            const dropLabel = document.getElementById('dropzone-label');
            const defaultContent = document.getElementById('default-dropzone-content');
            const selectedContent = document.getElementById('selected-dropzone-content');
            const displaySpan = document.getElementById('display-file-name');

            if (fileName) {
                // Sembunyikan default, munculkan kartu hijau
                defaultContent.classList.add('hidden');
                selectedContent.classList.remove('hidden');
                displaySpan.textContent = fileName;
                
                // Ubah border dropzone jadi hijau solid
                dropLabel.classList.remove('border-slate-300', 'border-dashed', 'bg-slate-50');
                dropLabel.classList.add('border-emerald-500', 'border-solid');
            } else {
                // Kembalikan ke semula jika batal pilih
                defaultContent.classList.remove('hidden');
                selectedContent.classList.add('hidden');
                dropLabel.classList.add('border-slate-300', 'border-dashed', 'bg-slate-50');
                dropLabel.classList.remove('border-emerald-500', 'border-solid');
            }
        });
    </script>

</body>
</html>