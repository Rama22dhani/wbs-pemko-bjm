<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Verifikator - WBS Banjarmasin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: {
                        'verif-dark': '#134e4a', // teal-900
                        'verif-primary': '#0f766e', // teal-700
                        'verif-light': '#f0fdfa', // teal-50
                        'verif-accent': '#14b8a6', // teal-500
                    }
                }
            }
        };
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased selection:bg-verif-accent selection:text-white"
    x-data="{ 
        tab: 'verifikasi', 
        showModalVerifikasi: false, 
        formVerif: { 
            id: '', 
            judul: '', 
            pelapor: '', 
            keputusan: 'terima', 
            tingkat_pelanggaran: '', 
            investigator_id: '', 
            catatan_verifikator: '' 
        } 
    }">

    <!-- Top Navigation -->
    <nav class="bg-white shadow-sm border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Title -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-verif-primary to-verif-accent rounded-xl flex items-center justify-center text-white shadow-md shadow-teal-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-slate-800 leading-tight">Portal Verifikator</h1>
                        <p class="text-[10px] uppercase tracking-wider font-semibold text-verif-primary">Pemko Banjarmasin</p>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center gap-4">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-slate-700">{{ Auth::user()->name }}</p>
                        <p class="text-xs font-medium text-slate-500 capitalize">{{ str_replace('_', ' ', Auth::user()->peran) }}</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-verif-light border border-teal-200 flex items-center justify-center text-verif-primary font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="w-px h-6 bg-slate-200 mx-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-slate-500 hover:text-red-500 transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        
        <!-- Welcome Hero -->
        <div class="bg-verif-dark rounded-3xl p-8 md:p-10 text-white relative overflow-hidden shadow-xl shadow-teal-900/10">
            <!-- Decorative blobs -->
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-verif-primary rounded-full blur-3xl opacity-50 mix-blend-screen pointer-events-none"></div>
            <div class="absolute bottom-0 left-10 -mb-20 w-60 h-60 bg-teal-400 rounded-full blur-3xl opacity-20 mix-blend-screen pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <h2 class="text-3xl font-bold mb-2">Selamat datang, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h2>
                    <p class="text-teal-100 text-sm md:text-base leading-relaxed mb-6">Anda berada di pusat kendali Verifikasi Kasus. Tugas Anda adalah menyaring laporan yang masuk serta mengetok palu putusan tindak lanjut berdasarkan hasil investigasi lapangan.</p>
                    
                    <div class="flex flex-wrap gap-3">
                        <button @click="tab = 'verifikasi'" 
                            :class="tab === 'verifikasi' ? 'bg-white text-verif-dark shadow-md' : 'bg-white/10 text-white hover:bg-white/20'"
                            class="px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-300 flex items-center gap-2">
                            <span class="relative flex h-3 w-3" x-show="{{ $kasusMasuk->count() }} > 0">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                            </span>
                            Antrean Verifikasi ({{ $kasusMasuk->count() }})
                        </button>
                        
                        <button @click="tab = 'tindak_lanjut'" 
                            :class="tab === 'tindak_lanjut' ? 'bg-white text-verif-dark shadow-md' : 'bg-white/10 text-white hover:bg-white/20'"
                            class="px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-300 flex items-center gap-2">
                            <span class="relative flex h-3 w-3" x-show="{{ $kasusPerluTindakLanjut->count() }} > 0">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                            </span>
                            Input Tindak Lanjut ({{ $kasusPerluTindakLanjut->count() }})
                        </button>
                    </div>
                </div>
                
                <div class="hidden lg:flex items-center justify-center p-6 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/20">
                    <div class="text-center">
                        <p class="text-6xl font-black mb-1">{{ $kasusMasuk->count() + $kasusPerluTindakLanjut->count() }}</p>
                        <p class="text-teal-100 text-xs font-semibold uppercase tracking-wider">Total Tugas Aktif</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 1: Antrean Verifikasi -->
        <div x-show="tab === 'verifikasi'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Laporan Masuk (Butuh Verifikasi)</h3>
                        <p class="text-sm text-slate-500 mt-1">Saring kelayakan laporan sebelum diteruskan ke Investigator.</p>
                    </div>
                </div>
                
                @if($kasusMasuk->isEmpty())
                <div class="p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-700">Kerja Bagus!</h4>
                    <p class="text-slate-500 text-sm mt-1 max-w-sm">Tidak ada laporan baru yang mengantre. Anda sudah menyelesaikan semua tugas verifikasi saat ini.</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="p-4 pl-6">Kode Tiket</th>
                                <th class="p-4">Tanggal Masuk</th>
                                <th class="p-4">Pelapor</th>
                                <th class="p-4 w-1/3">Judul Laporan</th>
                                <th class="p-4 text-center pr-6">Aksi Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($kasusMasuk as $km)
                            <tr class="hover:bg-verif-light/50 transition duration-200">
                                <td class="p-4 pl-6 font-mono font-bold text-verif-primary">{{ $km->kode_tiket }}</td>
                                <td class="p-4 text-slate-600">{{ $km->created_at->format('d M Y, H:i') }}</td>
                                <td class="p-4">
                                    @if($km->user_id)
                                        <span class="font-semibold text-slate-800">{{ $km->user->name }}</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold border border-slate-200">Anonim</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <p class="text-slate-800 font-medium line-clamp-2">{{ $km->judul_laporan }}</p>
                                </td>
                                <td class="p-4 text-center pr-6 flex justify-center gap-2">
                                    <a href="{{ route('verifikator.show', $km->id) }}" class="p-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl transition" title="Lihat Berkas">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <button @click='showModalVerifikasi = true; formVerif = {
                                        id: {{ $km->id }},
                                        judul: {{ json_encode($km->judul_laporan) }},
                                        pelapor: {{ json_encode($km->user->name ?? "Anonim") }},
                                        keputusan: "terima",
                                        tingkat_pelanggaran: "",
                                        investigator_id: "",
                                        catatan_verifikator: ""
                                    }' class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-verif-primary to-verif-accent hover:from-verif-accent hover:to-teal-400 text-white rounded-xl text-xs font-bold shadow-md shadow-teal-500/20 hover:-translate-y-0.5 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        VERIFIKASI
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        <!-- TAB 2: Antrean Tindak Lanjut -->
        <div x-show="tab === 'tindak_lanjut'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Menunggu Putusan Tindak Lanjut</h3>
                        <p class="text-sm text-slate-500 mt-1">Kasus yang telah selesai diinvestigasi dan menunggu sanksi akhir.</p>
                    </div>
                </div>
                
                @if($kasusPerluTindakLanjut->isEmpty())
                <div class="p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-700">Antrean Bersih</h4>
                    <p class="text-slate-500 text-sm mt-1 max-w-sm">Belum ada kasus yang berstatus menunggu tindak lanjut dari investigasi.</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="p-4 pl-6">Kode Tiket</th>
                                <th class="p-4 w-1/2">Kesimpulan Investigasi</th>
                                <th class="p-4 text-center pr-6">Aksi Penindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($kasusPerluTindakLanjut as $kpt)
                            <tr class="hover:bg-slate-50 transition duration-200">
                                <td class="p-4 pl-6 font-mono font-bold text-amber-600">{{ $kpt->kode_tiket }}</td>
                                <td class="p-4">
                                    <div class="bg-slate-100 p-3 rounded-lg border border-slate-200 text-slate-700 italic text-xs leading-relaxed line-clamp-3">
                                        "{{ $kpt->kesimpulan }}"
                                    </div>
                                </td>
                                <td class="p-4 text-center pr-6 flex justify-center gap-2 items-center h-full pt-6">
                                    <a href="{{ route('verifikator.show', $kpt->id) }}" class="p-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl transition" title="Lihat Berkas Lengkap">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('verifikator.tindaklanjut.edit', $kpt->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold shadow-md hover:-translate-y-0.5 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                                        Ketok Putusan Final
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
        
    </main>

    <!-- Modal Verifikasi Kasus -->
    <div x-show="showModalVerifikasi" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4" style="display: none;" x-transition>
        <div @click.away="showModalVerifikasi = false" class="bg-white rounded-2xl shadow-xl w-full max-w-xl overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
            <div class="bg-verif-dark p-5 border-b-4 border-verif-accent flex justify-between items-center sticky top-0 z-10">
                <div class="flex items-center gap-2">
                    <span class="text-xl">🛡️</span>
                    <h3 class="text-white font-bold text-lg">Panel Verifikasi Laporan</h3>
                </div>
                <button @click="showModalVerifikasi = false" class="text-slate-300 hover:text-white font-bold">✕</button>
            </div>

            <form :action="'/verifikator/kasus/' + formVerif.id + '/verifikasi'" method="POST" class="p-6 overflow-y-auto space-y-4">
                @csrf
                @method('PUT')

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 leading-tight">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Pelapor: <span class="text-verif-primary" x-text="formVerif.pelapor"></span></span>
                    <p class="text-sm font-black text-slate-800 mt-1.5 line-clamp-2" x-text="formVerif.judul"></p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Ambil Keputusan <span class="text-red-500">*</span></label>
                    <select name="keputusan" x-model="formVerif.keputusan" class="w-full bg-white border-2 border-slate-300 focus:border-verif-primary rounded-xl px-4 py-3 font-extrabold text-xs outline-none transition">
                        <option value="terima">🟢 TERIMA & DISPOSISIKAN KE INVESTIGATOR</option>
                        <option value="tolak">🔴 TOLAK & TUTUP KASUS INI</option>
                    </select>
                </div>

                <div x-show="formVerif.keputusan === 'terima'" x-transition class="space-y-4 pt-2 border-t border-slate-100">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Tingkat Kasus <span class="text-red-500">*</span></label>
                            <select name="tingkat_pelanggaran" x-model="formVerif.tingkat_pelanggaran" :required="formVerif.keputusan === 'terima'" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-bold focus:border-verif-primary outline-none">
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="Ringan">Ringan (Administrasi/Teguran)</option>
                                <option value="Sedang">Sedang (Etika/Disiplin)</option>
                                <option value="Berat">Berat (Pidana/Korupsi/Pungli)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Tugaskan Investigator <span class="text-red-500">*</span></label>
                            <select name="investigator_id" x-model="formVerif.investigator_id" :required="formVerif.keputusan === 'terima'" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-bold focus:border-verif-primary outline-none">
                                <option value="">-- Pilih Investigator --</option>
                                @foreach($dataPegawai as $inv)
                                    <option value="{{ $inv->id }}">{{ $inv->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1" x-text="formVerif.keputusan === 'terima' ? 'Instruksi Khusus untuk Investigator' : 'Alasan Laporan Pengaduan Ditolak'"></label>
                    <textarea name="catatan_verifikator" x-model="formVerif.catatan_verifikator" rows="3" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs focus:border-verif-primary outline-none" :placeholder="formVerif.keputusan === 'terima' ? 'Contoh: Segera periksa bukti rekaman CCTV di lokasi...' : 'Contoh: Bukti foto buram dan tidak menunjukkan identitas terlapor...'"></textarea>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="showModalVerifikasi = false" class="px-5 py-2.5 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                    <button type="submit" class="px-6 py-2.5 text-xs font-black text-white rounded-xl shadow-md transition transform hover:scale-105"
                        :class="formVerif.keputusan === 'terima' ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 shadow-emerald-500/30' : 'bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 shadow-red-500/30'"
                        x-text="formVerif.keputusan === 'terima' ? 'ACC & DISPOSISI' : 'KONFIRMASI TOLAK'">
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
