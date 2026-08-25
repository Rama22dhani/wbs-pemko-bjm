<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lacak Kasus Pegawai - Pemko Banjarmasin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bjm-blue': '#0f172a',
                        'bjm-gold': '#d97706',
                        'bjm-surface': '#1e293b',
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-bjm-blue text-gray-100 min-h-screen flex flex-col items-center pt-10 px-4 font-sans relative overflow-x-hidden">
    <a href="{{ url('/') }}" class="absolute top-4 left-4 sm:top-6 sm:left-6 flex items-center gap-2 text-slate-300 hover:text-white bg-white/10 hover:bg-white/20 border border-white/10 px-4 py-2 rounded-lg transition font-medium text-sm z-50 backdrop-blur-sm shadow-lg">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali
    </a>

    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-bjm-gold rounded-full blur-[150px] opacity-5 z-0 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-600 rounded-full blur-[150px] opacity-10 z-0 pointer-events-none"></div>

    <div class="text-center mb-10 z-10">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-3 mb-4 hover:opacity-80 transition">
            <div class="bg-gradient-to-br from-bjm-gold to-yellow-500 p-2 rounded-lg shadow-lg">
                <svg class="w-6 h-6 text-bjm-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">STATUS PENANGANAN KASUS</h1>
        </a>
        <p class="text-gray-400">Masukkan Kode Kasus untuk melacak progres penindakan pegawai.</p>
    </div>

    <div class="w-full max-w-xl bg-bjm-surface border border-gray-700 shadow-2xl rounded-2xl p-8 mb-8 z-10 relative">
        <form action="{{ route('lacak.cari') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <input type="text" name="kode_tiket" placeholder="Contoh: KASUS-BQSAA" 
                value="{{ $tiket ?? '' }}" required
                class="w-full bg-bjm-blue border border-gray-600 text-white placeholder-gray-500 rounded-xl px-4 py-3 focus:border-bjm-gold focus:ring-1 focus:ring-bjm-gold outline-none uppercase font-mono tracking-wider transition">
            
            <button type="submit" class="bg-bjm-gold hover:bg-yellow-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition transform hover:scale-105 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                CARI
            </button>
        </form>
    </div>

    @if(isset($laporan))
        @if($laporan)
            <div class="w-full max-w-3xl bg-bjm-surface border border-gray-700 shadow-2xl rounded-2xl p-8 z-10 relative animate-fade-in-up">
                
                <div class="flex flex-col md:flex-row justify-between items-start mb-8 border-b border-gray-700 pb-6 gap-4">
                    <div>
                        <span class="text-bjm-gold text-xs font-bold uppercase tracking-widest mb-1 block">Judul Laporan</span>
                        <h3 class="text-2xl font-bold text-white leading-tight">{{ $laporan->judul_laporan }}</h3>
                        <p class="text-gray-400 text-sm mt-1 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            Kasus: <span class="font-mono text-white bg-gray-700 px-2 rounded">{{ $laporan->kode_tiket }}</span>
                        </p>
                    </div>
                    <div class="text-left md:text-right">
                        <span class="inline-block bg-blue-900/50 text-blue-300 border border-blue-700/50 text-xs px-3 py-1 rounded-full uppercase font-bold tracking-wide">
                            {{ $laporan->kategori_laporan }}
                        </span>
                        <p class="text-xs text-gray-500 mt-2 flex items-center gap-1 md:justify-end">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $laporan->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>

                <div class="relative pl-4">
                    <div class="absolute top-4 left-[27px] w-0.5 h-[calc(100%-40px)] bg-gray-700 z-0"></div>

                    <div class="flex items-start mb-10 relative z-10">
                        <div class="flex-shrink-0 w-14 flex justify-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-green-500 shadow-[0_0_15px_rgba(34,197,94,0.5)]">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        <div class="ml-4 bg-bjm-blue/50 p-4 rounded-xl border border-gray-700 w-full">
                            <h4 class="font-bold text-green-400 text-lg">Laporan Diterima</h4>
                            <p class="text-sm text-gray-400 mt-1">Laporan Anda telah berhasil masuk ke dalam sistem kami.</p>
                        </div>
                    </div>

                    @if($laporan->status == 'ditolak')
                        <div class="flex items-start mb-4 relative z-10">
                            <div class="flex-shrink-0 w-14 flex justify-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-red-600 shadow-[0_0_15px_rgba(220,38,38,0.8)] border-2 border-white">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </div>
                            </div>
                            <div class="ml-4 bg-gradient-to-r from-red-950/80 to-bjm-blue/80 p-5 rounded-xl border border-red-500/50 w-full shadow-lg">
                                <span class="bg-red-500 text-white text-[10px] font-black tracking-widest px-2.5 py-0.5 rounded uppercase inline-block mb-1">Status Final</span>
                                <h4 class="font-bold text-red-400 text-lg">Laporan Pengaduan Ditolak</h4>
                                <p class="text-sm text-gray-300 mt-2 leading-relaxed">Mohon maaf, laporan pengaduan Anda tidak dapat dilanjutkan ke tahap investigasi oleh tim Verifikator kami.</p>
                                <div class="mt-4 p-3 bg-red-900/30 rounded-lg border border-red-700/40 text-xs text-red-200">
                                    <b>Alasan Penolakan:</b> Bukti awal yang dilampirkan tidak memenuhi kriteria kecukupan bukti, atau laporan berada di luar kewenangan penindakan instansi.
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-start mb-10 relative z-10">
                            <div class="flex-shrink-0 w-14 flex justify-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ in_array($laporan->status, ['investigasi', 'selesai']) ? 'bg-green-500 shadow-[0_0_15px_rgba(34,197,94,0.5)]' : 'bg-gray-700 border border-gray-600' }}">
                                    @if(in_array($laporan->status, ['investigasi', 'selesai']))
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <span class="text-gray-400 text-xs font-bold">2</span>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4 bg-bjm-blue/50 p-4 rounded-xl border border-gray-700 w-full {{ in_array($laporan->status, ['investigasi', 'selesai']) ? '' : 'opacity-60' }}">
                                <h4 class="font-bold {{ in_array($laporan->status, ['investigasi', 'selesai']) ? 'text-green-400' : 'text-gray-400' }} text-lg">Verifikasi & Investigasi</h4>
                                <p class="text-sm text-gray-400 mt-1">
                                    @if(in_array($laporan->status, ['investigasi', 'selesai']))
                                        Sedang ditangani oleh Investigator: <span class="text-white font-semibold">{{ $laporan->investigator->name ?? 'Tim Lapangan' }}</span>
                                    @else
                                        Menunggu verifikasi petugas.
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start relative z-10">
                            <div class="flex-shrink-0 w-14 flex justify-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $laporan->status == 'selesai' ? 'bg-green-500 shadow-[0_0_15px_rgba(34,197,94,0.5)]' : 'bg-gray-700 border border-gray-600' }}">
                                    @if($laporan->status == 'selesai')
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @else
                                        <span class="text-gray-400 text-xs font-bold">3</span>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4 bg-bjm-blue/50 p-4 rounded-xl border border-gray-700 w-full {{ $laporan->status == 'selesai' ? '' : 'opacity-60' }}">
                                <h4 class="font-bold {{ $laporan->status == 'selesai' ? 'text-green-400' : 'text-gray-400' }} text-lg">Selesai</h4>
                                
                                @if($laporan->status == 'selesai')
                                    <div class="mt-4 bg-slate-800/60 border border-slate-700/50 rounded-xl p-5 space-y-5">
                                        
                                        <div>
                                            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                Fakta & Kesimpulan Investigasi
                                            </h5>
                                            <p class="text-slate-300 text-sm italic leading-relaxed">
                                                "{{ $laporan->kesimpulan ?? 'Data kesimpulan belum diinput oleh tim investigator.' }}"
                                            </p>
                                        </div>
                                        
                                        <div class="pt-4 border-t border-slate-700/50">
                                            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                                                Keputusan Sanksi & Tindak Lanjut
                                            </h5>
                                            <p class="text-white text-sm font-medium leading-relaxed">
                                                {{ $laporan->tindak_lanjut ?? 'Menunggu keputusan akhir dan penjatuhan sanksi dari pihak berwenang.' }}
                                            </p>
                                            
                                            @if($laporan->pihak_penindak)
                                            <div class="mt-3 flex items-center gap-2 bg-slate-900/50 px-3 py-2 rounded-lg inline-flex">
                                                <span class="text-xs text-slate-500">Dieksekusi oleh:</span>
                                                <span class="text-xs font-bold text-amber-500">{{ $laporan->pihak_penindak }}</span>
                                            </div>
                                            @endif
                                        </div>

                                    </div>
                                @else
                                    <p class="text-sm text-gray-400 mt-1">Laporan belum ditutup.</p>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @else
            <div class="w-full max-w-xl bg-red-900/20 border border-red-500/50 text-red-200 p-6 rounded-2xl text-center shadow-lg">
                <p class="font-bold text-lg">Kasus Tidak Ditemukan</p>
                <p class="text-sm opacity-80 mt-1">Kode kasus <b>{{ $tiket }}</b> tidak terdaftar di dalam sistem kami.</p>
            </div>
        @endif
    @endif

    <div class="mt-12 text-gray-500 text-sm z-10 pb-8">&copy; 2026 Pemerintah Kota Banjarmasin.</div>
</body>
</html>