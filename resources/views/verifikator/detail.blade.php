<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Berkas Kasus - Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] }, colors: { 'bjm-dark': '#0f172a', 'bjm-gold': '#d97706', } } } }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen font-sans" x-data="{ tab: 'laporan' }">

    <!-- Header -->
    <header class="h-16 bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-20 sticky top-0">
        <a href="{{ route('verifikator.dashboard') }}" class="text-slate-500 hover:text-bjm-gold flex items-center gap-2 transition font-medium text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dashboard
        </a>
    </header>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-slate-900 via-bjm-dark to-slate-900 pt-10 pb-28 px-4 sm:px-6 lg:px-8 relative overflow-hidden border-b-4 border-bjm-gold">
        <!-- Abstract Pattern -->
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#d97706 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-bjm-gold/10 rounded-full blur-3xl"></div>
        <div class="absolute -left-20 top-20 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>

        <div class="max-w-5xl mx-auto flex flex-col sm:flex-row justify-between items-start sm:items-center relative z-10">
            <div class="mb-5 sm:mb-0">
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-8 h-8 rounded-full bg-bjm-gold/20 flex items-center justify-center text-bjm-gold shadow-inner">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </span>
                    <p class="text-slate-300 text-sm font-bold tracking-wide uppercase">Kode Kasus Pengaduan</p>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">#{{ $pengaduan->kode_tiket }}</h1>
            </div>
            
            <span class="px-5 py-2.5 rounded-xl font-bold text-sm tracking-wide uppercase shadow-lg shadow-black/20 flex items-center gap-2 border {{ $pengaduan->status == 'selesai' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : ($pengaduan->status == 'tindak_lanjut' ? 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30' : 'bg-white/10 text-white border-white/20') }}">
                <div class="w-2 h-2 rounded-full {{ $pengaduan->status == 'selesai' ? 'bg-emerald-400' : ($pengaduan->status == 'tindak_lanjut' ? 'bg-cyan-400' : 'bg-bjm-gold') }} animate-pulse"></div>
                {{ $pengaduan->status == 'tindak_lanjut' ? 'Menunggu Tindak Lanjut' : ucfirst($pengaduan->status) }}
            </span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 pb-12 relative z-10">
        
        <!-- Segmented Tabs -->
        <div class="mb-8 bg-white/90 backdrop-blur-md p-1.5 rounded-xl shadow-sm border border-slate-200 inline-flex space-x-1 overflow-x-auto w-full sm:w-auto">
            <button @click="tab = 'laporan'" :class="tab === 'laporan' ? 'bg-bjm-dark text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'" class="px-5 py-2.5 rounded-lg font-bold text-sm transition-all flex items-center gap-2 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Data Kasus
            </button>
            
            @if($pengaduan->status == 'selesai' || $pengaduan->status == 'investigasi' || $pengaduan->status == 'tindak_lanjut')
            <button @click="tab = 'investigasi'" :class="tab === 'investigasi' ? 'bg-bjm-dark text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'" class="px-5 py-2.5 rounded-lg font-bold text-sm transition-all flex items-center gap-2 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Hasil Investigasi
            </button>
            @endif
            
            @if($pengaduan->status == 'selesai')
            <button @click="tab = 'keputusan'" :class="tab === 'keputusan' ? 'bg-bjm-dark text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'" class="px-5 py-2.5 rounded-lg font-bold text-sm transition-all flex items-center gap-2 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Tindak Lanjut
            </button>
            @endif
        </div>

        <!-- TAB: DATA KASUS -->
        <div x-show="tab === 'laporan'" class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-10 shadow-lg shadow-slate-200/50 space-y-10" x-transition.opacity>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Col 1 -->
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                        <p class="text-xs text-slate-500 font-bold tracking-wider uppercase">Identitas Pelapor</p>
                    </div>
                    <p class="text-slate-800 font-extrabold text-lg mb-2">{{ $pengaduan->nama_pelapor }}</p>
                    <div class="space-y-2 mt-4">
                        <p class="text-slate-600 text-sm flex items-center gap-2"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> {{ $pengaduan->nomor_hp }}</p>
                        <p class="text-slate-600 text-sm flex items-center gap-2"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> {{ $pengaduan->email }}</p>
                        @if($pengaduan->nip)
                            <p class="text-slate-600 text-sm flex items-center gap-2 mt-3 pt-3 border-t border-slate-200"><span class="font-semibold text-slate-800">NIP:</span> {{ $pengaduan->nip }}</p>
                        @endif
                    </div>
                </div>
                
                <!-- Col 2 -->
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </span>
                        <p class="text-xs text-slate-500 font-bold tracking-wider uppercase">Detail Kejadian</p>
                    </div>
                    <div class="space-y-4 mt-4">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Lokasi Kejadian</p>
                            <p class="text-slate-800 font-semibold text-sm mt-0.5 leading-snug">{{ $pengaduan->lokasi_kejadian }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Tanggal Kejadian</p>
                            <p class="text-slate-800 font-semibold text-sm mt-0.5">{{ \Carbon\Carbon::parse($pengaduan->tanggal_kejadian)->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Kategori</p>
                            <p class="text-slate-800 font-semibold text-sm mt-0.5">{{ $pengaduan->kategori_laporan }}</p>
                        </div>
                    </div>
                </div>

                <!-- Col 3 -->
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 flex flex-col items-start hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </span>
                        <p class="text-xs text-slate-500 font-bold tracking-wider uppercase">Tingkat Pelanggaran</p>
                    </div>
                    <div class="mt-4 flex-grow flex items-center w-full">
                        <span class="px-5 py-3 w-full text-center text-sm rounded-xl font-extrabold border-2 shadow-sm uppercase tracking-wide {{ $pengaduan->tingkat_pelanggaran == 'Berat' ? 'bg-red-50 text-red-700 border-red-200' : ($pengaduan->tingkat_pelanggaran == 'Sedang' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200') }}">
                            {{ $pengaduan->tingkat_pelanggaran ?? 'Belum Ditentukan' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <div class="flex items-center gap-3 mb-5">
                    <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 shadow-sm border border-slate-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    </span>
                    <p class="text-xs text-slate-500 font-bold tracking-wider uppercase">Isi Laporan Utama</p>
                </div>
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <h3 class="text-slate-900 font-extrabold text-xl sm:text-2xl mb-5 pb-5 border-b border-slate-100 leading-tight">{{ $pengaduan->judul_laporan }}</h3>
                    <p class="text-slate-700 leading-relaxed whitespace-pre-line text-[15px] sm:text-base">{{ $pengaduan->isi_laporan }}</p>
                </div>
            </div>

            @if($pengaduan->pesan_susulan)
            <div class="mt-8 bg-gradient-to-br from-cyan-50 to-blue-50/50 border border-cyan-100 p-6 sm:p-8 rounded-2xl relative overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <div class="absolute -right-8 -top-8 opacity-10 transform rotate-12">
                    <svg class="w-48 h-48 text-cyan-600" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.714 4.141-8.609 9.983-8.609h.01V9h-.01c-2.457 0-4.639.816-6.196 2.373C16.326 12.846 15.65 14.925 15.65 17.5V21h-1.633zm-9.983 0v-7.391c0-5.714 4.141-8.609 9.983-8.609h.01V9h-.01c-2.457 0-4.639.816-6.196 2.373C4.326 12.846 3.65 14.925 3.65 17.5V21H2.017z"></path></svg>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="w-10 h-10 rounded-xl bg-cyan-100 text-cyan-700 flex items-center justify-center shrink-0 shadow-sm border border-cyan-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                        <p class="text-xs text-cyan-800 font-bold tracking-wider uppercase">Informasi Tambahan Pelapor</p>
                    </div>
                    <p class="text-cyan-900 italic font-medium text-base sm:text-lg leading-relaxed border-l-4 border-cyan-400 pl-5 py-2">"{{ $pengaduan->pesan_susulan }}"</p>
                    @if($pengaduan->lampiran_susulan)
                        <div class="mt-6">
                            <a href="{{ \Illuminate\Support\Str::startsWith($pengaduan->lampiran_susulan, ['bukti_susulan/', 'bukti_pengaduan/']) ? asset('storage/' . $pengaduan->lampiran_susulan) : asset('uploads/pengaduan/' . $pengaduan->lampiran_susulan) }}" target="_blank" class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-md shadow-cyan-600/30 transform hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                Lihat Lampiran Bukti Tambahan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <div class="flex gap-4 pt-8 mt-8 border-t border-slate-100">
                @if($pengaduan->lampiran_bukti)
                <a href="{{ asset('storage/' . $pengaduan->lampiran_bukti) }}" target="_blank" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white font-bold px-6 py-3.5 rounded-xl text-sm transition-all shadow-lg shadow-slate-300 transform hover:scale-105 border border-slate-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                    Lihat Berkas Bukti Awal
                </a>
                @endif
            </div>
        </div>

        <!-- TAB: INVESTIGASI -->
        @if($pengaduan->status == 'selesai' || $pengaduan->status == 'investigasi' || $pengaduan->status == 'tindak_lanjut')
        <div x-show="tab === 'investigasi'" style="display: none;" class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-10 shadow-lg shadow-slate-200/50 space-y-8" x-transition.opacity>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-slate-100 pb-8">
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Investigator Lapangan</p>
                    <p class="text-slate-800 font-extrabold text-lg flex items-center gap-2">
                        <span>🕵️‍♂️</span> {{ $pengaduan->investigator->name ?? 'Belum Ditugaskan' }}
                    </p>
                    <p class="text-slate-500 text-sm mt-1 ml-8">{{ $pengaduan->investigator->email ?? '' }}</p>
                </div>
                <div class="bg-purple-50 p-6 rounded-2xl border border-purple-100">
                    <p class="text-xs text-purple-600 uppercase font-bold mb-3">Bukti Temuan Lapangan</p>
                    @if($pengaduan->bukti_investigasi)
                        <a href="{{ asset('storage/' . $pengaduan->bukti_investigasi) }}" target="_blank" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-md shadow-purple-600/30 transform hover:scale-105">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            Lihat Lampiran Bukti
                        </a>
                    @else
                        <span class="text-purple-400 italic font-medium text-sm">Tidak ada lampiran bukti temuan</span>
                    @endif
                </div>
            </div>
            
            <div class="space-y-6">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-8 h-8 rounded-lg bg-bjm-gold/10 text-bjm-gold flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                        <p class="text-xs text-bjm-gold uppercase font-bold">Fakta Lapangan</p>
                    </div>
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 text-slate-700 leading-relaxed whitespace-pre-line text-[15px]">{{ $pengaduan->fakta_lapangan ?? '-' }}</div>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-8 h-8 rounded-lg bg-bjm-gold/10 text-bjm-gold flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </span>
                        <p class="text-xs text-bjm-gold uppercase font-bold">Pihak Terkait / Saksi</p>
                    </div>
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 text-slate-700 leading-relaxed whitespace-pre-line text-[15px]">{{ $pengaduan->pihak_terlibat ?? '-' }}</div>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-8 h-8 rounded-lg bg-bjm-gold/10 text-bjm-gold flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002 2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </span>
                        <p class="text-xs text-bjm-gold uppercase font-bold">Kesimpulan & Rekomendasi</p>
                    </div>
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 text-slate-700 leading-relaxed whitespace-pre-line text-[15px] font-medium">{{ $pengaduan->kesimpulan ?? '-' }}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- TAB: TINDAK LANJUT -->
        @if($pengaduan->status == 'selesai')
        <div x-show="tab === 'keputusan'" style="display: none;" class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-10 shadow-lg shadow-slate-200/50 space-y-10" x-transition.opacity>
            <!-- REKAP DATA KASUS -->
            <div>
                <p class="text-xs text-slate-500 uppercase font-extrabold tracking-wider mb-4 border-b border-slate-100 pb-3 flex items-center gap-2"><span class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded">1</span> Data Kasus Laporan</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-100 mb-6 hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Judul Laporan</p>
                        <p class="text-sm font-bold text-slate-800 mt-1">{{ $pengaduan->judul_laporan }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Kategori & Tanggal Kejadian</p>
                        <p class="text-sm font-medium text-slate-700 mt-1">{{ $pengaduan->kategori_laporan }} <br> <span class="text-slate-500">{{ \Carbon\Carbon::parse($pengaduan->tanggal_kejadian)->format('d M Y') }}</span></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Tingkat Pelanggaran</p>
                        <div class="mt-2">
                            @if($pengaduan->tingkat_pelanggaran)
                                <span class="px-3 py-1.5 text-xs uppercase rounded-lg font-bold border 
                                    {{ $pengaduan->tingkat_pelanggaran == 'Berat' ? 'bg-red-50 text-red-600 border-red-200' : 
                                    ($pengaduan->tingkat_pelanggaran == 'Sedang' ? 'bg-amber-50 text-amber-600 border-amber-200' : 
                                    'bg-emerald-50 text-emerald-600 border-emerald-200') }}">
                                    {{ $pengaduan->tingkat_pelanggaran }}
                                </span>
                            @else
                                <span class="text-slate-400 italic text-xs font-bold">-</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-100 hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Lokasi Kejadian</p>
                        <p class="text-sm font-medium text-slate-700 mt-1">{{ $pengaduan->lokasi_kejadian }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Kronologi Kejadian</p>
                        <p class="text-sm text-slate-700 mt-1 whitespace-pre-line leading-relaxed">{{ $pengaduan->isi_laporan }}</p>
                    </div>
                </div>
            </div>

            <!-- REKAP HASIL INVESTIGASI -->
            <div>
                <p class="text-xs text-blue-600 uppercase font-extrabold tracking-wider mb-4 border-b border-slate-100 pb-3 flex items-center gap-2"><span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded">2</span> Hasil Investigasi Lapangan</p>
                <div class="bg-gradient-to-br from-blue-50/80 to-indigo-50/50 p-6 rounded-2xl border border-blue-100 shadow-sm space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wide">Fakta Lapangan</p>
                            <p class="text-[13px] text-slate-700 mt-1.5 whitespace-pre-line leading-relaxed">{{ $pengaduan->fakta_lapangan ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wide">Pihak Terlibat</p>
                            <p class="text-[13px] text-slate-700 mt-1.5 whitespace-pre-line leading-relaxed">{{ $pengaduan->pihak_terlibat ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="pt-5 border-t border-blue-200/60">
                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wide mb-2">Kesimpulan Tim Investigasi</p>
                        <p class="text-[15px] text-slate-800 font-semibold italic border-l-4 border-blue-400 pl-4 py-1">"{{ $pengaduan->kesimpulan ?? 'Tidak ada kesimpulan' }}"</p>
                        <p class="text-xs font-semibold text-slate-500 mt-3 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Oleh: {{ $pengaduan->investigator->name ?? 'Tim Lapangan' }}</p>
                    </div>
                </div>
            </div>

            <!-- REKAP ARSIP BUKTI -->
            <div>
                <p class="text-xs text-slate-500 uppercase font-extrabold tracking-wider mb-4 border-b border-slate-100 pb-3 flex items-center gap-2"><span class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded">3</span> Arsip Bukti Terlampir</p>
                <div class="flex flex-wrap gap-4">
                    @if($pengaduan->lampiran_bukti)
                    <a href="{{ asset('storage/' . $pengaduan->lampiran_bukti) }}" target="_blank" class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 font-bold px-5 py-2.5 rounded-xl text-[13px] transition-all shadow-sm hover:shadow-md">
                        📎 Bukti Awal Laporan
                    </a>
                    @else
                    <span class="inline-flex items-center gap-2 bg-slate-50 text-slate-400 border border-slate-200 font-medium px-5 py-2.5 rounded-xl text-[13px]">
                        ❌ Tidak ada Bukti Awal
                    </span>
                    @endif

                    @if($pengaduan->lampiran_susulan)
                    <a href="{{ \Illuminate\Support\Str::startsWith($pengaduan->lampiran_susulan, ['bukti_susulan/', 'bukti_pengaduan/']) ? asset('storage/' . $pengaduan->lampiran_susulan) : asset('uploads/pengaduan/' . $pengaduan->lampiran_susulan) }}" target="_blank" class="inline-flex items-center gap-2 bg-cyan-50 hover:bg-cyan-100 text-cyan-700 border border-cyan-200 font-bold px-5 py-2.5 rounded-xl text-[13px] transition-all shadow-sm hover:shadow-md">
                        📎 Bukti Tambahan
                    </a>
                    @else
                    <span class="inline-flex items-center gap-2 bg-slate-50 text-slate-400 border border-slate-200 font-medium px-5 py-2.5 rounded-xl text-[13px]">
                        ❌ Tidak ada Bukti Tambahan
                    </span>
                    @endif

                    @if($pengaduan->bukti_investigasi)
                    <a href="{{ asset('storage/' . $pengaduan->bukti_investigasi) }}" target="_blank" class="inline-flex items-center gap-2 bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 font-bold px-5 py-2.5 rounded-xl text-[13px] transition-all shadow-sm hover:shadow-md">
                        📷 Bukti Temuan Lapangan
                    </a>
                    @else
                    <span class="inline-flex items-center gap-2 bg-slate-50 text-slate-400 border border-slate-200 font-medium px-5 py-2.5 rounded-xl text-[13px]">
                        ❌ Tidak ada Bukti Investigasi
                    </span>
                    @endif
                </div>
            </div>

            <!-- EKSEKUSI TINDAK LANJUT -->
            <div>
                <p class="text-xs text-emerald-600 uppercase font-extrabold tracking-wider mb-4 border-b border-emerald-100 pb-3 flex items-center gap-2"><span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded">4</span> Keputusan & Eksekusi Tindak Lanjut</p>
                
                <div class="bg-emerald-50 rounded-2xl border border-emerald-100 p-6 sm:p-8 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6 pb-6 border-b border-emerald-200/60">
                        <div>
                            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wide">Instansi Penindak</p>
                            <p class="text-slate-800 font-extrabold text-lg flex items-center gap-2 mt-1.5">
                                <span class="text-emerald-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg></span> 
                                {{ $pengaduan->pihak_penindak ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wide">Tanggal Eksekusi Keputusan</p>
                            <p class="text-slate-800 font-extrabold text-lg flex items-center gap-2 mt-1.5">
                                <span class="text-emerald-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></span> 
                                {{ $pengaduan->tanggal_tindak_lanjut ? \Carbon\Carbon::parse($pengaduan->tanggal_tindak_lanjut)->format('d F Y') : '-' }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wide mb-2">Detail Sanksi / Tindak Lanjut</p>
                        <div class="text-emerald-900 leading-relaxed whitespace-pre-line text-[15px] font-medium">{{ $pengaduan->tindak_lanjut ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</body>
</html>
