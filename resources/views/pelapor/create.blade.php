<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Pengaduan - Portal Pelaporan Pegawai</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bjm-blue': '#0f172a',
                        'bjm-gold': '#d97706',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 min-h-screen pb-12">

    <!-- NAVBAR HEADER -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('pelapor.dashboard') }}" class="text-slate-500 hover:text-bjm-gold transition flex items-center gap-2 font-bold text-sm bg-slate-100 hover:bg-yellow-50 px-3 py-1.5 rounded-lg border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Dasbor
                    </a>
                </div>

                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-bjm.png') }}" alt="Pemko Banjarmasin" class="w-8 h-auto hidden sm:block">
                    <div class="text-right leading-tight">
                        <h1 class="text-bjm-blue font-black text-sm tracking-wide">PORTAL PELAPORAN</h1>
                        <p class="text-bjm-gold text-[9px] uppercase font-bold tracking-widest">KOTA BANJARMASIN</p>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <!-- AREA KONTEN FORMULIR -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 md:p-10 relative overflow-hidden" x-data="{ kategori: '' }">
            
            <div class="flex items-start sm:items-center gap-4 mb-8 border-b border-slate-200 pb-6">
                <div class="w-12 h-12 rounded-xl bg-bjm-gold/10 flex items-center justify-center text-bjm-gold border border-bjm-gold/20 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div>
                    <h2 class="text-xl md:text-2xl font-black text-slate-800">Formulir Pembuatan Kasus Baru</h2>
                    <p class="text-slate-500 text-sm mt-1">Identitas Anda terenkripsi dengan aman. Mohon isi data kejadian sesuai dengan fakta.</p>
                </div>
            </div>

            <form action="{{ route('pelapor.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Data User Otomatis (Hidden) -->
                <input type="hidden" name="nama_pelapor" value="{{ $user->name }}">
                <input type="hidden" name="email" value="{{ $user->email }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-2">Judul Indikasi Kasus <span class="text-red-500">*</span></label>
                        <input type="text" name="judul_laporan" required placeholder="Contoh: Indikasi Pungli di Layanan X..." 
                            class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl px-4 py-3 text-sm focus:border-bjm-gold focus:bg-white focus:ring-4 focus:ring-bjm-gold/10 outline-none transition-all shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-2">Klasifikasi Pelanggaran <span class="text-red-500">*</span></label>
                        <select name="kategori_laporan" x-model="kategori" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl px-4 py-3 text-sm focus:border-bjm-gold focus:bg-white focus:ring-4 focus:ring-bjm-gold/10 outline-none transition-all shadow-sm">
                            <option value="">-- Pilih Klasifikasi Terdekat --</option>
                            <option value="Pungli">Pungli (Pungutan Liar)</option>
                            <option value="Korupsi">Korupsi</option>
                            <option value="Gratifikasi">Gratifikasi / Suap</option>
                            <option value="Pelanggaran Disiplin">Pelanggaran Disiplin ASN</option>
                            <option value="Benturan Kepentingan">Benturan Kepentingan (Nepotisme)</option>
                            <option value="Penyalahgunaan Wewenang">Penyalahgunaan Wewenang & Aset</option>
                            <option value="Lainnya">Lainnya (Tulis manual)</option>
                        </select>
                        <div x-show="kategori === 'Lainnya'" x-transition class="mt-3 bg-amber-50 p-4 rounded-xl border border-amber-200 shadow-inner">
                            <label class="block text-xs font-black text-amber-900 uppercase mb-1.5">
                                Tuliskan Jenis Pelanggaran <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="kategori_lainnya" :required="kategori === 'Lainnya'" class="w-full bg-white border border-amber-300 rounded-lg px-4 py-2.5 text-sm font-bold text-slate-800 outline-none focus:ring-2 focus:ring-bjm-gold placeholder:font-normal placeholder:text-slate-400" placeholder="Ketik jenis pelanggaran di sini...">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-2">No. HP/WhatsApp (Aktif) <span class="text-red-500">*</span></label>
                        <input type="text" name="nomor_hp" required placeholder="08xxxxxxxx" 
                            class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl px-4 py-3 text-sm focus:border-bjm-gold focus:bg-white focus:ring-4 focus:ring-bjm-gold/10 outline-none transition-all shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-2">NIP Terlapor (Jika Diketahui)</label>
                        <input type="text" name="nip" placeholder="Ketik NIP terlapor (Opsional)" 
                            class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl px-4 py-3 text-sm focus:border-bjm-gold focus:bg-white focus:ring-4 focus:ring-bjm-gold/10 outline-none transition-all shadow-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-2">Tanggal Terjadinya <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_kejadian" required 
                            class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl px-4 py-3 text-sm focus:border-bjm-gold focus:bg-white focus:ring-4 focus:ring-bjm-gold/10 outline-none transition-all shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-2">Lokasi / Instansi Kejadian <span class="text-red-500">*</span></label>
                        <input type="text" name="lokasi_kejadian" required placeholder="Contoh: Ruang Arsip Dinas X..." 
                            class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl px-4 py-3 text-sm focus:border-bjm-gold focus:bg-white focus:ring-4 focus:ring-bjm-gold/10 outline-none transition-all shadow-sm">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-2 flex items-center gap-2">
                        Kronologi Kejadian Pelanggaran <span class="text-red-500">*</span>
                    </label>
                    <textarea name="isi_laporan" rows="5" required placeholder="Uraikan tindakan pelanggaran secara detail, sertakan waktu spesifik, dan nama-nama saksi jika ada..." 
                        class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl p-4 text-sm focus:border-bjm-gold focus:bg-white focus:ring-4 focus:ring-bjm-gold/10 outline-none transition-all shadow-sm"></textarea>
                </div>

                <div class="mb-8 bg-blue-50/50 border border-blue-100 p-5 rounded-xl">
                    <label class="block text-xs font-black text-blue-900 uppercase mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                        Unggah File Bukti (Foto/Dokumen)
                    </label>
                    <p class="text-[11px] text-blue-600/70 font-semibold mb-3">Format: JPG, PNG, PDF, DOC, DOCX. Maksimal ukuran: 5MB.</p>
                    <input type="file" name="lampiran_bukti" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="block w-full text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 bg-white border border-blue-200 rounded-xl shadow-sm transition-all"/>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-slate-200 mt-6">
                    <!-- Tombol Batal -->
                    <a href="{{ route('pelapor.dashboard') }}" 
                        class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl font-bold text-slate-500 bg-slate-100 border border-slate-200 hover:bg-slate-200 hover:text-slate-800 transition-colors duration-200 text-sm">
                        Batal
                    </a>
                    
                    <!-- Tombol Kirim Pengaduan -->
                    <button type="submit" 
                            class="group inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl font-bold text-white bg-bjm-blue hover:bg-slate-800 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 focus:ring-4 focus:ring-bjm-blue/30 outline-none text-sm">
                        <span>Kirim Pengaduan Kasus</span>
                        <svg class="w-4 h-4 transform transition-transform duration-300 group-hover:translate-x-1 group-hover:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>
</html>