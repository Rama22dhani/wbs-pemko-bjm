<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai - Pemko Banjarmasin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // PALET WARNA RESMI (BIRU & EMAS)
                        'bjm-blue': '#0f172a',    // Biru Tua Sangat Gelap (Slate 900) - Dominan
                        'bjm-gold': '#d97706',    // Emas (Amber 600) - Aksen Utama
                        'bjm-gold-light': '#f59e0b', // Emas Terang (Amber 500) - Hover
                        'bjm-surface': '#1e293b', // Biru Abu-abu (Slate 800) - Kartu/Surface
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50">

    <nav class="absolute w-full z-50 top-0 left-0 px-6 py-6 transition-all duration-300">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/logo-bjm.png') }}" alt="Pemko Banjarmasin" class="w-12 h-auto drop-shadow-lg">
            <div>
                <h1 class="text-white font-extrabold text-lg sm:text-xl leading-none tracking-tight">MANAJEMEN PELANGGARAN DAN PELAPORAN PEGAWAI</h1>
                <p class="text-gray-300 text-[10px] sm:text-xs font-medium tracking-widest uppercase mt-1">Pemerintah Kota Banjarmasin</p>
            </div>
        </div>
            <div class="flex items-center gap-6">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-white font-medium hover:text-bjm-gold transition">Dasbor</a>
                    @else
                        <a href="{{ route('login') }}" class="text-white font-medium hover:text-bjm-gold transition hidden sm:block">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-bjm-gold hover:bg-bjm-gold-light text-white font-bold py-2.5 px-6 rounded-lg transition shadow-lg shadow-orange-500/20 transform hover:-translate-y-0.5">
                                Daftar
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <section class="relative min-h-[95vh] md:min-h-screen flex items-center justify-center overflow-hidden bg-bjm-blue" style="background-image: url('{{ asset("images/pemko-balai-kota.png") }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        
        <div class="absolute inset-0 bg-bjm-blue/80 mix-blend-multiply z-0"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-bjm-blue via-bjm-blue/40 to-transparent z-0"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 w-full grid grid-cols-1 lg:grid-cols-12 gap-12 items-center pt-28 pb-32">
            
            <div class="lg:col-span-7 text-left space-y-8">
                
                <div class="inline-flex items-center gap-2 bg-bjm-surface/80 backdrop-blur-sm border border-gray-600 rounded-full px-4 py-1.5 shadow-sm">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span class="text-gray-300 text-xs font-semibold tracking-wide uppercase">Manajemen Pelaporan Internal Pemko</span>
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight">
                    Integritas ASN Yang <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-bjm-gold to-yellow-200">Terproteksi.</span>
                </h1>
                
                <p class="text-gray-300 text-base sm:text-lg lg:text-xl max-w-xl leading-relaxed">
                    Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai di lingkungan Pemerintah Kota Banjarmasin. Laporkan indikasi pelanggaran secara rahasia, profesional, dan terpantau.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <!-- Tombol Buat Pengaduan (Primary Action) -->
                    <a href="{{ route('pelapor.create') }}" 
                        class="group relative z-10 inline-flex items-center justify-center gap-3 px-8 py-4 rounded-xl font-extrabold text-white text-base sm:text-lg bg-gradient-to-r from-bjm-gold to-amber-600 shadow-xl shadow-amber-600/30 transition-all duration-300 transform hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-amber-500/60 w-full md:w-auto">
                        <svg class="w-6 h-6 transition-transform duration-300 group-hover:scale-125" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="tracking-wide">Buat Pengaduan Baru</span>
                    </a>
                    
                    <!-- Tombol Lacak Status Kasus (Secondary Action) -->
                    <a href="{{ route('lacak') }}" 
                        class="group relative z-10 inline-flex items-center justify-center gap-3 px-8 py-4 rounded-xl font-bold text-white text-base sm:text-lg bg-white/5 backdrop-blur-md border border-gray-500 hover:border-bjm-gold hover:bg-white/10 shadow-lg hover:shadow-bjm-gold/20 transition-all duration-300 transform hover:-translate-y-1.5 w-full md:w-auto">
                        <svg class="w-6 h-6 text-gray-300 group-hover:text-bjm-gold transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="tracking-wide">Lacak Status Kasus</span>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5 hidden lg:block relative">
                <div class="relative bg-bjm-surface/80 border border-gray-600 rounded-2xl p-6 shadow-2xl backdrop-blur-md z-20 transform rotate-1 hover:rotate-0 transition duration-500">
                    
                    <div class="flex justify-between items-center mb-6 border-b border-gray-600 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        <p class="text-gray-300 text-xs font-mono">Pantauan Audit Sistem</p>
                    </div>

                    <div class="space-y-4">
                        @forelse($laporanTerbaru as $laporan)
                            <div class="flex items-start gap-4 p-3 rounded-lg bg-bjm-blue/60 border border-gray-600">
                                @if($laporan->status == 'selesai')
                                    <div class="bg-green-500/20 p-2 rounded text-green-400 mt-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                @elseif($laporan->status == 'investigasi')
                                    <div class="bg-blue-500/20 p-2 rounded text-blue-400 mt-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                @else
                                    <div class="bg-yellow-500/20 p-2 rounded text-yellow-400 mt-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                @endif
                                
                                <div>
                                    <h4 class="text-white text-sm font-semibold">{{ Str::limit($laporan->judul_laporan, 25) }}</h4>
                                    <p class="text-gray-400 text-xs mt-1">Kasus {{ $laporan->kode_tiket }} • Status: {{ ucfirst($laporan->status) }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="p-3 text-center">
                                <p class="text-gray-400 text-xs italic">Belum ada laporan pengaduan masuk.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-600">
                        <div class="w-full h-2 bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-bjm-gold transition-all duration-1000" style="width: <?= $persentase ?>%;"></div>
                        </div>
                        <p class="text-right text-gray-400 text-xs mt-2">Enkripsi Database: Aktif</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <div class="relative z-20 max-w-7xl mx-auto px-6 -mt-20 lg:-mt-16">
        <div class="bg-bjm-surface/95 backdrop-blur-xl border border-gray-700 rounded-2xl shadow-2xl p-6 lg:p-10 flex flex-col md:flex-row justify-between items-center gap-8 divide-y md:divide-y-0 md:divide-x divide-gray-700">
            
            <div class="flex items-center gap-4 px-4 w-full md:w-auto">
                <div class="p-3 bg-bjm-blue rounded-lg text-bjm-gold"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>
                <div>
                    <h3 class="text-3xl font-bold text-white">{{ $totalLaporan }}</h3>
                    <p class="text-gray-400 text-sm mt-1">Kasus Terdaftar</p>
                </div>
            </div>
            
            <div class="flex items-center gap-4 px-4 w-full md:w-auto pt-6 md:pt-0">
                <div class="p-3 bg-bjm-blue rounded-lg text-bjm-gold"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg></div>
                <div>
                    <h3 class="text-3xl font-bold text-white">{{ $persentase }}%</h3>
                    <p class="text-gray-400 text-sm mt-1">Tingkat Penyelesaian</p>
                </div>
            </div>

            <div class="flex items-center gap-4 px-4 w-full md:w-auto pt-6 md:pt-0">
                <div class="p-3 bg-bjm-blue rounded-lg text-bjm-gold"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                <div>
                    <h3 class="text-3xl font-bold text-white">24/7</h3>
                    <p class="text-gray-400 text-sm mt-1">Akses Pengaduan</p>
                </div>
            </div>

            <div class="flex items-center gap-4 px-4 w-full md:w-auto pt-6 md:pt-0">
                <div class="p-3 bg-bjm-blue rounded-lg text-bjm-gold"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg></div>
                <div>
                    <h3 class="text-3xl font-bold text-white">100%</h3>
                    <p class="text-gray-400 text-sm mt-1">Anonimitas Pelapor</p>
                </div>
            </div>

        </div>
    </div>

    <section class="pt-32 pb-24 bg-white -mt-16 relative z-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16 max-w-2xl mx-auto">
                <span class="text-bjm-gold font-bold tracking-wider uppercase text-sm">Prosedur Pelaporan</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-bjm-blue mt-2 mb-4">Mekanisme Penanganan Kasus</h2>
                <p class="text-gray-500 text-lg">Proses terstruktur untuk menjamin setiap laporan pelanggaran pegawai ditangani secara objektif dan tuntas.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="group bg-gray-50 p-8 rounded-2xl border border-gray-200 hover:shadow-xl hover:border-bjm-gold/30 transition duration-300 hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-blue-50 w-24 h-24 rounded-bl-full -mr-4 -mt-4 transition group-hover:bg-bjm-gold/10"></div>
                    <div class="w-14 h-14 bg-white border border-gray-200 text-bjm-blue rounded-xl flex items-center justify-center mb-6 text-xl font-bold shadow-sm group-hover:border-bjm-gold group-hover:text-bjm-gold transition relative z-10">1</div>
                    <h3 class="text-xl font-bold text-bjm-blue mb-3 relative z-10">Pendaftaran Kasus</h3>
                    <p class="text-gray-500 leading-relaxed relative z-10">Pelapor mengirimkan indikasi pelanggaran disertai bukti awal. Identitas pelapor dijamin kerahasiaannya.</p>
                </div>

                <div class="group bg-gray-50 p-8 rounded-2xl border border-gray-200 hover:shadow-xl hover:border-bjm-gold/30 transition duration-300 hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-blue-50 w-24 h-24 rounded-bl-full -mr-4 -mt-4 transition group-hover:bg-bjm-gold/10"></div>
                    <div class="w-14 h-14 bg-white border border-gray-200 text-bjm-blue rounded-xl flex items-center justify-center mb-6 text-xl font-bold shadow-sm group-hover:border-bjm-gold group-hover:text-bjm-gold transition relative z-10">2</div>
                    <h3 class="text-xl font-bold text-bjm-blue mb-3 relative z-10">Verifikasi & Audit</h3>
                    <p class="text-gray-500 leading-relaxed relative z-10">Admin melakukan telaah kelayakan bukti, dilanjutkan investigasi lapangan oleh tim verifikator.</p>
                </div>

                <div class="group bg-gray-50 p-8 rounded-2xl border border-gray-200 hover:shadow-xl hover:border-bjm-gold/30 transition duration-300 hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-blue-50 w-24 h-24 rounded-bl-full -mr-4 -mt-4 transition group-hover:bg-bjm-gold/10"></div>
                    <div class="w-14 h-14 bg-white border border-gray-200 text-bjm-blue rounded-xl flex items-center justify-center mb-6 text-xl font-bold shadow-sm group-hover:border-bjm-gold group-hover:text-bjm-gold transition relative z-10">3</div>
                    <h3 class="text-xl font-bold text-bjm-blue mb-3 relative z-10">Tindak Lanjut Final</h3>
                    <p class="text-gray-500 leading-relaxed relative z-10">Penjatuhan keputusan sanksi bagi terlapor yang terbukti, atau rehabilitasi nama baik jika tidak terbukti.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-bjm-blue text-white py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-center md:text-left">
                    <h2 class="text-xl sm:text-2xl font-extrabold mb-1 tracking-tight">APLIKASI MANAJEMEN PELANGGARAN DAN PELAPORAN PEGAWAI</h2>
                    <p class="text-gray-400 text-sm">Pemerintah Kota Banjarmasin</p>
                </div>
                
                <div class="flex gap-6">
                    <a href="#" class="text-gray-400 hover:text-white transition">Tentang Sistem</a>
                    <a href="#" class="text-gray-400 hover:text-white transition">Bantuan</a>
                    <a href="#" class="text-gray-400 hover:text-white transition">Protokol Keamanan</a>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-10 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                <p>&copy; 2026 Pemerintah Kota Banjarmasin. Hak cipta dilindungi undang-undang.</p>
                <p>Dinas Komunikasi, Informatika dan Statistik</p>
            </div>
        </div>
    </footer>

</body>
</html>