<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai - Pemerintah Kota Banjarmasin</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bjm-dark': '#090d16',
                        'bjm-blue': '#0f172a',
                        'bjm-surface': '#162032',
                        'bjm-card': '#1e293b',
                        'bjm-gold': '#d97706',
                        'bjm-gold-light': '#f59e0b',
                        'bjm-gold-dark': '#b45309',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom Utilities & Subtle Animations */
        @media (prefers-reduced-motion: reduce) {
            *, ::before, ::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        .reveal-element {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .reveal-element.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .gold-glow {
            box-shadow: 0 0 50px -10px rgba(217, 119, 6, 0.3);
        }

        .card-subtle-glow:hover {
            box-shadow: 0 20px 35px -10px rgba(15, 23, 42, 0.08), 0 0 1px 1px rgba(217, 119, 6, 0.2);
        }

        /* Ambient Hero Background Glow */
        .hero-glow-1 {
            background: radial-gradient(circle, rgba(217, 119, 6, 0.15) 0%, rgba(217, 119, 6, 0) 70%);
        }
        .hero-glow-2 {
            background: radial-gradient(circle, rgba(37, 99, 235, 0.18) 0%, rgba(37, 99, 235, 0) 70%);
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 selection:bg-amber-500 selection:text-white flex flex-col min-h-screen">

    <!-- ========================================================= -->
    <!-- 1. HEADER / NAVIGATION BAR                                -->
    <!-- ========================================================= -->
    <header id="site-header" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-bjm-dark/85 backdrop-blur-md border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo & Institution Name -->
                <a href="{{ url('/') }}" class="flex items-center gap-3.5 group focus:outline-none">
                    <img src="{{ asset('images/logo-bjm.png') }}" 
                         alt="Logo Pemko Banjarmasin" 
                         class="w-10 sm:w-11 h-auto drop-shadow-md transition-transform duration-300 group-hover:scale-105 flex-shrink-0">
                    <div class="flex flex-col">
                        <span class="text-white font-extrabold text-xs sm:text-sm md:text-base tracking-tight leading-tight group-hover:text-amber-400 transition-colors uppercase">
                            Manajemen Pelanggaran dan Pelaporan Pegawai
                        </span>
                        <span class="text-slate-400 text-[10px] sm:text-[11px] font-medium tracking-wider uppercase mt-0.5">
                            Pemerintah Kota Banjarmasin
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-300">
                    <a href="#prosedur" class="hover:text-amber-400 transition-colors">Prosedur</a>
                    <a href="#komitmen" class="hover:text-amber-400 transition-colors">Jaminan Keamanan</a>
                    <a href="#faq" class="hover:text-amber-400 transition-colors">FAQ</a>
                    <a href="{{ route('lacak') }}" class="hover:text-amber-400 transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Lacak Kasus
                    </a>
                </nav>

                <!-- Desktop Action Buttons -->
                <div class="hidden sm:flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" 
                               class="inline-flex items-center gap-2 bg-slate-800/90 hover:bg-slate-700 text-white text-xs sm:text-sm font-semibold px-4 py-2.5 rounded-lg border border-slate-700 hover:border-amber-500/50 transition-all shadow-sm">
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                                <span>Dasbor Saya</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="text-slate-200 hover:text-white text-xs sm:text-sm font-semibold px-3 py-2 rounded-lg hover:bg-white/5 transition-colors">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="inline-flex items-center justify-center bg-gradient-to-r from-amber-600 to-amber-500 hover:from-amber-500 hover:to-amber-400 text-white text-xs sm:text-sm font-bold px-4 sm:px-5 py-2.5 rounded-lg transition-all shadow-md shadow-amber-900/20 hover:shadow-amber-600/30 hover:-translate-y-0.5">
                                    Daftar Akun
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center gap-2 md:hidden">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-xs font-semibold bg-amber-600 hover:bg-amber-500 text-white px-3 py-1.5 rounded-md">
                            Dasbor
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-200 hover:text-white px-2 py-1.5">
                            Masuk
                        </a>
                    @endauth
                    <button type="button" 
                            id="mobile-menu-btn"
                            aria-label="Buka Menu" 
                            class="p-2 text-slate-300 hover:text-white rounded-lg hover:bg-slate-800/80 focus:outline-none">
                        <svg class="w-6 h-6" id="menu-icon-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                        <svg class="w-6 h-6 hidden" id="menu-icon-close" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

            </div>

            <!-- Mobile Dropdown Menu -->
            <div id="mobile-menu" class="hidden md:hidden border-t border-slate-800 py-4 space-y-2 bg-bjm-dark/95 backdrop-blur-xl px-2">
                <a href="#prosedur" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800">Prosedur Penanganan</a>
                <a href="#komitmen" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800">Jaminan Keamanan</a>
                <a href="#faq" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800">Pertanyaan Umum (FAQ)</a>
                <a href="{{ route('lacak') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-amber-400 hover:bg-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Lacak Status Kasus
                </a>
                <div class="pt-3 border-t border-slate-800 flex flex-col gap-2">
                    @guest
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="w-full text-center py-2.5 rounded-lg bg-amber-600 text-white font-bold text-sm shadow-md">
                                Registrasi Pelapor
                            </a>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </header>


    <!-- ========================================================= -->
    <!-- 2. HERO SECTION                                           -->
    <!-- ========================================================= -->
    <section class="relative min-h-[92vh] flex items-center justify-center pt-28 pb-28 md:pt-36 md:pb-36 bg-bjm-dark overflow-hidden">
        
        <!-- Background Imagery & Ambient Gradients (Kantor Pemko Balai Kota Terlihat Jelas) -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <img src="{{ asset('images/pemko-balai-kota.png') }}" 
                 alt="Balai Kota Banjarmasin" 
                 class="w-full h-full object-cover object-[center_32%] opacity-90 brightness-[0.82] contrast-[1.06] scale-100 transition-all duration-700">
            
            <!-- Scrim Gradien Kiri untuk Keterbacaan Teks (Kanan Terbuka Terang untuk Gedung Pemko) -->
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/75 via-45% to-slate-950/25 lg:to-transparent"></div>
            
            <!-- Gradien Atas (Navbar) & Bawah (Transisi ke Statistik) -->
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/75 via-transparent via-25% to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/50 to-transparent"></div>
            
            <!-- Soft Ambient Light Glows -->
            <div class="absolute top-1/4 -left-32 w-96 h-96 hero-glow-1 rounded-full pointer-events-none opacity-40"></div>
            <div class="absolute bottom-10 right-0 w-[30rem] h-[30rem] hero-glow-2 rounded-full pointer-events-none opacity-30"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                <!-- Left: Headline & Actions -->
                <div class="lg:col-span-7 text-left space-y-7 reveal-element is-visible">
                    
                    <!-- Pill Tag / Status Badge -->
                    <div class="inline-flex items-center gap-2.5 bg-slate-900/90 border border-slate-700/80 rounded-full px-4 py-1.5 shadow-sm backdrop-blur-sm">
                        <span class="flex h-2.5 w-2.5 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span class="text-slate-300 text-xs font-semibold tracking-wide uppercase">
                            Saluran Resmi Whistleblowing System (WBS) Pemko Banjarmasin
                        </span>
                    </div>

                    <!-- Main Catchphrase -->
                    <h1 class="text-3xl sm:text-5xl lg:text-[3.25rem] font-extrabold text-white leading-[1.15] tracking-tight drop-shadow-md">
                        Kawal Integritas ASN, <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-amber-200 to-yellow-100">
                            Terlindungi & Terpercaya.
                        </span>
                    </h1>

                    <!-- Description -->
                    <p class="text-slate-200 text-base sm:text-lg max-w-xl leading-relaxed font-normal drop-shadow-sm">
                        Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai di lingkungan Pemerintah Kota Banjarmasin. Laporkan indikasi pelanggaran disiplin dan kode etik ASN secara rahasia, profesional, dan objektif.
                    </p>

                    <!-- Dual CTAs -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-2">
                        <!-- Primary CTA: Buat Pengaduan -->
                        <a href="{{ route('pelapor.create') }}" 
                           class="group relative inline-flex items-center justify-center gap-3 px-7 py-4 rounded-xl font-bold text-white text-base bg-gradient-to-r from-amber-600 via-amber-500 to-yellow-600 hover:from-amber-500 hover:to-yellow-500 shadow-xl shadow-amber-900/30 hover:shadow-amber-500/25 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 text-center">
                            <svg class="w-5 h-5 text-white transition-transform duration-300 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Buat Laporan Baru</span>
                        </a>

                        <!-- Secondary CTA: Lacak Status -->
                        <a href="{{ route('lacak') }}" 
                           class="group inline-flex items-center justify-center gap-3 px-7 py-4 rounded-xl font-semibold text-slate-100 hover:text-white text-base bg-slate-900/80 hover:bg-slate-800/90 border border-slate-700/90 hover:border-slate-500 shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 backdrop-blur-md text-center">
                            <svg class="w-5 h-5 text-slate-300 group-hover:text-amber-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Lacak Status Kasus</span>
                        </a>
                    </div>

                    <!-- Trust Pillars Micro -->
                    <div class="pt-4 flex flex-wrap items-center gap-y-2 gap-x-6 text-xs text-slate-300">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Identitas Terenkripsi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Bebas Tekanan & Intimidasi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Investigasi Objektif</span>
                        </div>
                    </div>

                </div>

                <!-- Right: Live System Monitor Card -->
                <div class="lg:col-span-5 relative reveal-element is-visible">
                    <div class="relative bg-slate-900/90 border border-slate-700/80 rounded-2xl p-6 sm:p-7 shadow-2xl backdrop-blur-xl transition-all duration-300 hover:border-slate-600">
                        
                        <!-- Top Monitor Bar -->
                        <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-800">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></div>
                                <span class="ml-2 text-slate-300 text-xs font-semibold tracking-wide">Pantauan Sistem Audit</span>
                            </div>
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-mono text-emerald-400 bg-emerald-950/60 px-2 py-0.5 rounded border border-emerald-800/40">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                AKTIF
                            </span>
                        </div>

                        <!-- Case Activity Stream -->
                        <div class="space-y-3">
                            <p class="text-[11px] font-medium uppercase tracking-wider text-slate-400 mb-2">Aktivitas Laporan Terkini</p>

                            @forelse($laporanTerbaru as $laporan)
                                <div class="p-3.5 rounded-xl bg-slate-800/70 border border-slate-700/70 hover:border-slate-600 transition-colors flex items-start gap-3.5">
                                    @if($laporan->status == 'selesai')
                                        <div class="w-9 h-9 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 flex-shrink-0 mt-0.5">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    @elseif($laporan->status == 'investigasi')
                                        <div class="w-9 h-9 rounded-lg bg-sky-500/10 border border-sky-500/30 flex items-center justify-center text-sky-400 flex-shrink-0 mt-0.5">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        </div>
                                    @elseif($laporan->status == 'tindak_lanjut')
                                        <div class="w-9 h-9 rounded-lg bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400 flex-shrink-0 mt-0.5">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                        </div>
                                    @else
                                        <div class="w-9 h-9 rounded-lg bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400 flex-shrink-0 mt-0.5">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                    @endif
                                    
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between gap-2">
                                            <h4 class="text-white text-sm font-semibold truncate">{{ Str::limit($laporan->judul_laporan, 28) }}</h4>
                                            <span class="text-[10px] font-mono font-semibold px-2 py-0.5 rounded bg-slate-700/70 text-slate-300">
                                                {{ $laporan->kode_tiket }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs text-slate-400">Status:</span>
                                            <span class="text-xs font-medium 
                                                @if($laporan->status == 'selesai') text-emerald-400
                                                @elseif($laporan->status == 'investigasi') text-sky-400
                                                @elseif($laporan->status == 'tindak_lanjut') text-indigo-400
                                                @else text-amber-400 @endif">
                                                {{ $laporan->status == 'tindak_lanjut' ? 'Tindak Lanjut' : ucfirst($laporan->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center rounded-xl bg-slate-800/40 border border-dashed border-slate-700">
                                    <svg class="w-8 h-8 text-slate-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-slate-400 text-xs italic">Belum ada aktivitas pengaduan baru.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Progress Bar & Security Indicator -->
                        <div class="mt-6 pt-5 border-t border-slate-800">
                            <div class="flex justify-between items-center text-xs mb-2">
                                <span class="text-slate-300 font-medium">Efektivitas Penyelesaian Kasus</span>
                                <span class="text-amber-400 font-bold font-mono">{{ $persentase }}%</span>
                            </div>
                            <div class="w-full h-2 bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-amber-600 to-amber-400 rounded-full transition-all duration-1000 ease-out" style="width: {{ $persentase }}%;"></div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-400 mt-3 pt-1">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    Enkripsi Database: AES-256
                                </span>
                                <span class="font-mono text-slate-400">Audit Validated</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ========================================================= -->
    <!-- 3. METRICS / STATS OVERVIEW                               -->
    <!-- ========================================================= -->
    <section class="relative z-20 -mt-12 sm:-mt-14 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="bg-slate-900/95 backdrop-blur-xl border border-slate-700/80 rounded-2xl shadow-2xl p-6 sm:p-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 divide-y sm:divide-y-0 sm:divide-x divide-slate-800">
                
                <!-- Stat Item 1 -->
                <div class="flex items-center gap-4 pt-4 sm:pt-0 sm:px-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight tabular-nums">{{ $totalLaporan }}</div>
                        <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Total Kasus Masuk</p>
                    </div>
                </div>

                <!-- Stat Item 2 -->
                <div class="flex items-center gap-4 pt-4 sm:pt-0 sm:px-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight tabular-nums">{{ $persentase }}%</div>
                        <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Tingkat Penyelesaian</p>
                    </div>
                </div>

                <!-- Stat Item 3 -->
                <div class="flex items-center gap-4 pt-4 sm:pt-0 sm:px-3">
                    <div class="w-12 h-12 rounded-xl bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">24/7</div>
                        <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Akses Pelaporan</p>
                    </div>
                </div>

                <!-- Stat Item 4 -->
                <div class="flex items-center gap-4 pt-4 sm:pt-0 sm:px-3">
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">100%</div>
                        <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Kerahasiaan Pelapor</p>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ========================================================= -->
    <!-- 4. MEKANISME & PROSEDUR PENANGANAN                        -->
    <!-- ========================================================= -->
    <section id="prosedur" class="pt-28 pb-20 bg-slate-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Title -->
            <div class="text-center max-w-2xl mx-auto mb-16 reveal-element">
                <span class="text-amber-600 font-bold tracking-wider uppercase text-xs sm:text-sm bg-amber-50 border border-amber-200/80 px-3.5 py-1 rounded-full">
                    Tata Cara & Regulasi
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3.5 mb-3 tracking-tight">
                    Mekanisme Penanganan Laporan
                </h2>
                <p class="text-slate-600 text-base sm:text-lg leading-relaxed">
                    Setiap indikasi pelanggaran yang dilaporkan diproses melalui alur baku secara objektif, berjenjang, dan akuntabel.
                </p>
            </div>

            <!-- Steps Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                
                <!-- Step 1 -->
                <div class="reveal-element bg-white p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:border-amber-500/40 transition-all duration-300 transform hover:-translate-y-1 group relative flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 flex items-center justify-center font-extrabold text-lg group-hover:bg-amber-600 group-hover:text-white transition-colors">
                                01
                            </div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tahap Pertama</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-amber-600 transition-colors">
                            Pendaftaran Laporan
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Pelapor menginput data kronologi dugaan pelanggaran beserta bukti awal (dokumen, foto, atau rekaman). Identitas pelapor dilindungi kerahasiaannya demi keamanan.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center text-xs font-medium text-amber-600">
                        <span>Mendapatkan Kode Tiket Unik</span>
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="reveal-element bg-white p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:border-amber-500/40 transition-all duration-300 transform hover:-translate-y-1 group relative flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 flex items-center justify-center font-extrabold text-lg group-hover:bg-amber-600 group-hover:text-white transition-colors">
                                02
                            </div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tahap Kedua</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-amber-600 transition-colors">
                            Verifikasi & Investigasi
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Tim verifikator memeriksa kelayakan bukti. Jika memenuhi kriteria, berkas diteruskan ke tim investigator independen untuk pemeriksaan faktual mendalam.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center text-xs font-medium text-amber-600">
                        <span>Pemeriksaan Bukti Faktual</span>
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="reveal-element bg-white p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:border-amber-500/40 transition-all duration-300 transform hover:-translate-y-1 group relative flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 flex items-center justify-center font-extrabold text-lg group-hover:bg-amber-600 group-hover:text-white transition-colors">
                                03
                            </div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tahap Ketiga</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-amber-600 transition-colors">
                            Penindakan & Rekomendasi
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Pemberian sanksi kedinasan bagi oknum yang terbukti melanggar ketentuan, atau pemulihan nama baik apabila dugaan tidak berdasar. Hasil akhir disampaikan pada sistem.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center text-xs font-medium text-amber-600">
                        <span>Penyelesaian Status Kasus</span>
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>

            </div>

        </div>
    </section>


    <!-- ========================================================= -->
    <!-- 5. JAMINAN KEAMANAN & PRINSIP PRIVASI                      -->
    <!-- ========================================================= -->
    <section id="komitmen" class="py-24 bg-slate-900 text-white relative overflow-hidden">
        
        <!-- Ambient Decor -->
        <div class="absolute inset-0 z-0 pointer-events-none opacity-40">
            <div class="absolute -top-24 right-0 w-96 h-96 bg-amber-600/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-10 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-16 reveal-element">
                <span class="text-amber-400 font-bold tracking-wider uppercase text-xs sm:text-sm bg-amber-500/10 border border-amber-500/30 px-3.5 py-1 rounded-full">
                    Perlindungan Whistleblower
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-3.5 mb-3 tracking-tight">
                    Komitmen Keamanan & Privasi
                </h2>
                <p class="text-slate-400 text-base sm:text-lg leading-relaxed">
                    Pemerintah Kota Banjarmasin menjamin perlindungan menyeluruh bagi setiap pelapor yang beritikad baik demi kemajuan birokrasi yang bersih.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Guarantee 1 -->
                <div class="reveal-element bg-slate-800/60 border border-slate-700/80 rounded-2xl p-7 hover:border-amber-500/40 transition-all duration-300 hover:bg-slate-800/90">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Kerahasiaan Identitas 100%</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Data identitas pelapor dienkripsi secara ketat di server dan hanya dapat diakses oleh tim pemeriksa yang disumpah tanpa campur tangan pihak luar.
                    </p>
                </div>

                <!-- Guarantee 2 -->
                <div class="reveal-element bg-slate-800/60 border border-slate-700/80 rounded-2xl p-7 hover:border-amber-500/40 transition-all duration-300 hover:bg-slate-800/90">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Bebas Intimidasi Kedinasan</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Pelapor ASN dilindungi hak kedinasannya sesuai peraturan perundang-undangan dan bebas dari ancaman demosi, mutasi sepihak, atau sanksi balasan.
                    </p>
                </div>

                <!-- Guarantee 3 -->
                <div class="reveal-element bg-slate-800/60 border border-slate-700/80 rounded-2xl p-7 hover:border-amber-500/40 transition-all duration-300 hover:bg-slate-800/90">
                    <div class="w-12 h-12 rounded-xl bg-sky-500/10 border border-sky-500/30 text-sky-400 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Pemeriksaan Objektif & Netral</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Setiap proses investigasi bertumpu pada bukti otentik, mematuhi asas praduga tak bersalah, dan diawasi langsung oleh Inspektorat Kota.
                    </p>
                </div>

            </div>

        </div>
    </section>


    <!-- ========================================================= -->
    <!-- 6. FAQ (PERTANYAAN UMUM)                                   -->
    <!-- ========================================================= -->
    <section id="faq" class="py-24 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-14 reveal-element">
                <span class="text-amber-600 font-bold tracking-wider uppercase text-xs sm:text-sm bg-amber-50 border border-amber-200 px-3.5 py-1 rounded-full">
                    Pusat Informasi
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3.5 mb-3 tracking-tight">
                    Pertanyaan yang Sering Diajukan
                </h2>
                <p class="text-slate-600 text-base leading-relaxed">
                    Jawaban ringkas terkait tata cara pengaduan, keamanan identitas, dan pelacakan status laporan.
                </p>
            </div>

            <div class="space-y-4 reveal-element">
                
                <!-- FAQ Item 1 -->
                <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50/50 transition-colors">
                    <button type="button" 
                            class="faq-button w-full px-6 py-4 text-left flex justify-between items-center gap-4 text-slate-900 font-semibold focus:outline-none"
                            aria-expanded="false">
                        <span>Bagaimana cara memantau tindak lanjut laporan saya?</span>
                        <svg class="w-5 h-5 text-slate-500 transform transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content hidden px-6 pb-5 text-slate-600 text-sm leading-relaxed border-t border-slate-200/60 pt-3">
                        Setiap laporan berhasil disimpan, Anda akan menerima <strong>Kode Tiket Unik</strong> (contoh: KASUS-XXXXX). Anda dapat memasukkan kode tersebut pada menu <a href="{{ route('lacak') }}" class="text-amber-600 font-semibold underline">Lacak Kasus</a> kapan saja untuk mengetahui riwayat dan progres investigasi.
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50/50 transition-colors">
                    <button type="button" 
                            class="faq-button w-full px-6 py-4 text-left flex justify-between items-center gap-4 text-slate-900 font-semibold focus:outline-none"
                            aria-expanded="false">
                        <span>Bukti apa saja yang dapat dilampirkan dalam pengaduan?</span>
                        <svg class="w-5 h-5 text-slate-500 transform transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content hidden px-6 pb-5 text-slate-600 text-sm leading-relaxed border-t border-slate-200/60 pt-3">
                        Bukti dapat berupa dokumen PDF/Word, foto/gambar kejadian, tangkapan layar percakapan, kwitansi, atau rekaman suara dan video yang memperkuat kronologi kejadian yang dilaporkan.
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50/50 transition-colors">
                    <button type="button" 
                            class="faq-button w-full px-6 py-4 text-left flex justify-between items-center gap-4 text-slate-900 font-semibold focus:outline-none"
                            aria-expanded="false">
                        <span>Siapa yang bertindak menangani laporan yang masuk?</span>
                        <svg class="w-5 h-5 text-slate-500 transform transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-content hidden px-6 pb-5 text-slate-600 text-sm leading-relaxed border-t border-slate-200/60 pt-3">
                        Laporan dikelola langsung oleh Tim Verifikator dan Unit Investigasi Terpadu di lingkungan Inspektorat serta Badan Kepegawaian Daerah Pemerintah Kota Banjarmasin secara profesional dan independen.
                    </div>
                </div>

            </div>

        </div>
    </section>


    <!-- ========================================================= -->
    <!-- 7. FINAL CALL-TO-ACTION BANNER                            -->
    <!-- ========================================================= -->
    <section class="py-16 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white relative border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal-element">
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-3">
                Mari Wujudkan Birokrasi Bersih & Akuntabel
            </h2>
            <p class="text-slate-400 max-w-2xl mx-auto text-sm sm:text-base mb-8">
                Peran aktif Anda adalah kunci perbaikan layanan publik di Pemerintah Kota Banjarmasin. Laporkan pelanggaran yang Anda ketahui tanpa rasa cemas.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ route('pelapor.create') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-amber-600 to-amber-500 hover:from-amber-500 hover:to-amber-400 text-white font-bold px-7 py-3.5 rounded-xl shadow-lg transition transform hover:-translate-y-0.5 text-sm sm:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Pengaduan Sekarang
                </a>
                <a href="{{ route('lacak') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold px-6 py-3.5 rounded-xl border border-slate-700 transition text-sm sm:text-base">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Periksa Kode Tiket
                </a>
            </div>
        </div>
    </section>


    <!-- ========================================================= -->
    <!-- 8. FOOTER                                                 -->
    <!-- ========================================================= -->
    <footer class="bg-bjm-dark text-white pt-14 pb-10 border-t border-slate-800/90 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-10 border-b border-slate-800/80">
                
                <!-- Col 1: Identity & Crest -->
                <div class="md:col-span-6 space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-bjm.png') }}" alt="Logo Pemko Banjarmasin" class="w-10 h-auto">
                        <div>
                            <p class="font-extrabold text-sm sm:text-base tracking-tight leading-tight text-white uppercase">
                                Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai
                            </p>
                            <p class="text-slate-400 text-xs mt-0.5">Pemerintah Kota Banjarmasin</p>
                        </div>
                    </div>
                    <p class="text-slate-400 text-xs leading-relaxed max-w-md">
                        Mendukung implementasi tata kelola pemerintahan yang bersih, transparan, dan bebas dari praktik maladministrasi maupun pelanggaran disiplin ASN.
                    </p>
                </div>

                <!-- Col 2: Navigation Links -->
                <div class="md:col-span-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-200 mb-3">Tautan Cepat</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="#prosedur" class="hover:text-amber-400 transition-colors">Prosedur Pengaduan</a></li>
                        <li><a href="#komitmen" class="hover:text-amber-400 transition-colors">Jaminan Kerahasiaan</a></li>
                        <li><a href="{{ route('lacak') }}" class="hover:text-amber-400 transition-colors">Lacak Status Kasus</a></li>
                        @guest
                            <li><a href="{{ route('login') }}" class="hover:text-amber-400 transition-colors">Masuk ke Akun</a></li>
                        @else
                            <li><a href="{{ url('/dashboard') }}" class="hover:text-amber-400 transition-colors">Dasbor Pengguna</a></li>
                        @endguest
                    </ul>
                </div>

                <!-- Col 3: Institutional Contact & Support -->
                <div class="md:col-span-3 space-y-2">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-200 mb-3">Unit Pelaksana</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Inspektorat Kota Banjarmasin bersama Diskominfotik Pemerintah Kota Banjarmasin.
                    </p>
                    <div class="pt-2 text-xs text-slate-500 font-mono">
                        Protokol TLS/SSL 256-bit Terverifikasi
                    </div>
                </div>

            </div>

            <!-- Bottom Legal Bar -->
            <div class="pt-6 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} Pemerintah Kota Banjarmasin. Hak Cipta Dilindungi Undang-Undang.</p>
                <p class="text-slate-400">Dinas Komunikasi, Informatika dan Statistik</p>
            </div>
        </div>
    </footer>


    <!-- ========================================================= -->
    <!-- 9. JAVASCRIPT LOGIC (Subtle Animations, Sticky Nav, FAQ)  -->
    <!-- ========================================================= -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            // 1. Mobile Menu Toggle
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const iconOpen = document.getElementById('menu-icon-open');
            const iconClose = document.getElementById('menu-icon-close');

            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', () => {
                    const isHidden = mobileMenu.classList.contains('hidden');
                    if (isHidden) {
                        mobileMenu.classList.remove('hidden');
                        iconOpen.classList.add('hidden');
                        iconClose.classList.remove('hidden');
                    } else {
                        mobileMenu.classList.add('hidden');
                        iconOpen.classList.remove('hidden');
                        iconClose.classList.add('hidden');
                    }
                });

                // Close mobile menu when link is clicked
                mobileMenu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => {
                        mobileMenu.classList.add('hidden');
                        iconOpen.classList.remove('hidden');
                        iconClose.classList.add('hidden');
                    });
                });
            }

            // 2. Sticky Header Elevation on Scroll
            const header = document.getElementById('site-header');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 20) {
                    header.classList.add('shadow-lg', 'bg-bjm-dark/95');
                    header.classList.remove('bg-bjm-dark/85');
                } else {
                    header.classList.remove('shadow-lg', 'bg-bjm-dark/95');
                    header.classList.add('bg-bjm-dark/85');
                }
            }, { passive: true });

            // 3. Scroll Reveal Animation using IntersectionObserver
            const revealElements = document.querySelectorAll('.reveal-element');
            if ('IntersectionObserver' in window) {
                const revealObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    root: null,
                    threshold: 0.12,
                    rootMargin: '0px 0px -40px 0px'
                });

                revealElements.forEach(el => {
                    // Elements inside hero are already visible by default
                    if (!el.classList.contains('is-visible')) {
                        revealObserver.observe(el);
                    }
                });
            } else {
                // Fallback for browsers without IntersectionObserver
                revealElements.forEach(el => el.classList.add('is-visible'));
            }

            // 4. FAQ Accordion Interaction
            const faqButtons = document.querySelectorAll('.faq-button');
            faqButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const content = btn.nextElementSibling;
                    const arrow = btn.querySelector('svg');
                    const isExpanded = btn.getAttribute('aria-expanded') === 'true';

                    // Close all other accordions (clean, non-cluttered UX)
                    faqButtons.forEach(otherBtn => {
                        if (otherBtn !== btn) {
                            otherBtn.setAttribute('aria-expanded', 'false');
                            otherBtn.nextElementSibling.classList.add('hidden');
                            const otherArrow = otherBtn.querySelector('svg');
                            if (otherArrow) otherArrow.classList.remove('rotate-180');
                        }
                    });

                    // Toggle current
                    if (isExpanded) {
                        btn.setAttribute('aria-expanded', 'false');
                        content.classList.add('hidden');
                        if (arrow) arrow.classList.remove('rotate-180');
                    } else {
                        btn.setAttribute('aria-expanded', 'true');
                        content.classList.remove('hidden');
                        if (arrow) arrow.classList.add('rotate-180');
                    }
                });
            });

        });
    </script>
</body>
</html>