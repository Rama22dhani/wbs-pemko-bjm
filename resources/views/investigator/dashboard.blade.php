<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dasbor Investigator - Sistem Pelaporan Pelanggaran</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bjm-blue': '#0f172a',    // Biru Tua Identitas
                        'bjm-surface': '#1e293b', // Biru Aksen
                        'bjm-gold': '#d97706',    // Emas Identitas
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 min-h-screen">

    <nav class="bg-bjm-blue border-b-4 border-bjm-gold sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-bjm-gold p-1.5 rounded-lg shadow-md text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <span class="font-bold text-lg tracking-wide text-white">RUANG INVESTIGASI</span>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-bjm-gold uppercase tracking-wider font-bold">Investigator Lapangan</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-white/10 hover:bg-red-500 text-red-400 hover:text-white p-2.5 rounded-full transition duration-300" title="Keluar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6 border-b border-slate-200 pb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-bjm-blue">Misi Anda, {{ explode(' ', Auth::user()->name)[0] }}! 🕵️‍♂️</h1>
                <p class="text-slate-500 mt-1.5 text-lg">Daftar kasus aktif yang perlu ditangani segera.</p>
            </div>
            
            <div class="flex gap-4 w-full md:w-auto">
                <div class="bg-gradient-to-br from-white to-slate-100 px-6 py-4 rounded-xl border border-slate-200 border-l-4 border-l-blue-500 shadow-md flex items-center gap-4 w-full md:w-auto">
                    <div class="p-3 bg-blue-50 rounded-lg text-blue-500 border border-blue-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total Kasus Aktif</p>
                        <p class="font-black text-bjm-blue text-3xl leading-none mt-1">{{ $kasusSaya->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl shadow-sm flex items-center gap-3">
            <div class="bg-emerald-100 p-1.5 rounded-lg text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
        @endif

        @if($kasusSaya->isEmpty())
            <div class="bg-gradient-to-b from-white to-slate-100 border border-slate-200 rounded-2xl p-16 text-center shadow-md">
                <div class="w-24 h-24 bg-white shadow-sm rounded-full flex items-center justify-center mx-auto mb-5 border border-slate-100">
                    <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-bjm-blue">Tidak Ada Kasus Aktif</h3>
                <p class="text-slate-500 mt-2 text-lg">Santai sejenak! Belum ada tugas baru yang diserahkan dari tim Verifikasi.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($kasusSaya as $kasus)
                <div class="bg-gradient-to-b from-white to-slate-50 border border-slate-200 rounded-2xl overflow-hidden shadow-md hover:shadow-xl hover:border-bjm-gold/50 transition duration-300 group flex flex-col relative">
                    
                    <div class="absolute top-0 right-0 mt-4 mr-4 z-10">
                        <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1.5 rounded-lg border shadow-sm
                            {{ $kasus->tingkat_pelanggaran == 'Berat' ? 'bg-red-50 text-red-600 border-red-200' : ($kasus->tingkat_pelanggaran == 'Sedang' ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-emerald-50 text-emerald-600 border-emerald-200') }}">
                            {{ $kasus->tingkat_pelanggaran ?? 'Umum' }}
                        </span>
                    </div>

                    <div class="p-7 border-b border-slate-100 bg-white relative">
                        <p class="font-mono text-slate-500 text-xs font-bold bg-slate-100 border border-slate-200 px-2.5 py-1 rounded w-fit mb-3">
                            {{ $kasus->kode_tiket }}
                        </p>
                        <h3 class="text-xl font-black text-bjm-blue line-clamp-2 leading-snug group-hover:text-amber-600 transition pr-16">
                            {{ $kasus->judul_laporan }}
                        </h3>
                        <p class="text-xs font-semibold text-slate-500 mt-3 flex items-start gap-1.5">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="line-clamp-2">{{ $kasus->lokasi_kejadian }}</span>
                        </p>
                    </div>

                    <div class="p-7 flex-grow flex flex-col justify-between">
                        <div class="bg-slate-100 p-4 rounded-xl border border-slate-200 mb-6 shadow-inner">
                            <p class="text-slate-600 text-sm line-clamp-3 leading-relaxed italic">
                                "{{ $kasus->isi_laporan }}"
                            </p>
                        </div>
                        
                        <div class="flex items-center justify-between text-xs font-bold text-slate-500 mb-6">
                            <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> {{ explode(' ', $kasus->nama_pelapor)[0] }}</span>
                            <span class="flex items-center gap-1.5 bg-slate-100 px-2 py-1 rounded border border-slate-200"><svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> {{ $kasus->created_at->format('d M') }}</span>
                        </div>

                        <a href="{{ route('investigator.show', $kasus->id) }}" class="block w-full bg-bjm-gold hover:bg-amber-600 text-white text-center font-bold py-3.5 rounded-xl transition shadow-md shadow-amber-500/20 flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Masukkan Hasil Investigasi
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

    </div>
</body>
</html>