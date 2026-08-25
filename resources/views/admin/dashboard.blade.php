<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dasbor Admin - Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        'bjm-dark': '#0f172a',
                        'bjm-light': '#1e293b',
                        'bjm-gold': '#d97706',
                    }
                }
            }
        };

        function getFileBadgeInfo(url) {
            if (!url) return { icon: '📁', label: 'File', class: 'bg-slate-100 text-slate-700 border-slate-300' };
            const cleanUrl = url.split('?')[0].split('#')[0];
            const ext = cleanUrl.split('.').pop().toLowerCase();
            if (ext === 'pdf') {
                return { icon: '📄', label: 'PDF', class: 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100' };
            } else if (['doc', 'docx'].includes(ext)) {
                return { icon: '📝', label: 'Word', class: 'bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100' };
            } else if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                return { icon: '📷', label: 'Foto', class: 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' };
            }
            return { icon: '📁', label: 'File', class: 'bg-slate-100 text-slate-700 border-slate-300 hover:bg-slate-200' };
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased" x-data="{ tab: 'beranda', sidebarOpen: false }">

    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden" style="display: none;"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 w-64 bg-bjm-dark text-slate-300 transition-transform duration-300 z-50 lg:translate-x-0 flex flex-col shadow-xl">
        
        <div class="h-16 flex items-center gap-3 px-5 border-b border-slate-700/50 bg-slate-900/50">
            <img src="{{ asset('images/logo-bjm.png') }}" alt="Pemko Banjarmasin" class="w-10 h-auto">
            <div class="leading-tight">
                <span class="text-white font-bold text-[15px] tracking-wide block">Admin Pengawasan</span>
                <span class="text-bjm-gold text-[10px] uppercase font-bold tracking-widest block">Kota Banjarmasin</span>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            
            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-4">Menu Utama</p>
            <button @click="tab = 'beranda'; sidebarOpen = false" :class="tab === 'beranda' ? 'bg-bjm-gold/10 text-bjm-gold border-l-4 border-bjm-gold' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm font-medium transition-colors">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Beranda Admin
            </button>

            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Master Data</p>
            <button @click="tab = 'pegawai'; sidebarOpen = false" :class="tab === 'pegawai' ? 'bg-bjm-gold/10 text-bjm-gold border-l-4 border-bjm-gold' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm font-medium transition-colors">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Data Akses (Akun)
            </button>
            <button @click="tab = 'pengguna'; sidebarOpen = false" :class="tab === 'pengguna' ? 'bg-bjm-gold/10 text-bjm-gold border-l-4 border-bjm-gold' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm font-medium transition-colors">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Data Pelapor
            </button>
            <button @click="tab = 'master_pegawai'; sidebarOpen = false" :class="tab === 'master_pegawai' ? 'bg-bjm-gold/10 text-bjm-gold border-l-4 border-bjm-gold' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm font-medium transition-colors">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                Data Pegawai
            </button>

            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Data Layanan Pengaduan</p>
            <button @click="tab = 'kasus'; sidebarOpen = false" :class="tab === 'kasus' ? 'bg-bjm-gold/10 text-bjm-gold border-l-4 border-bjm-gold' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm font-medium transition-colors">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Data Kasus
            </button>
            <button @click="tab = 'investigasi'; sidebarOpen = false" :class="tab === 'investigasi' ? 'bg-bjm-gold/10 text-bjm-gold border-l-4 border-bjm-gold' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm font-medium transition-colors">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Data Investigasi
            </button>
            <button @click="tab = 'input_tindaklanjut'; sidebarOpen = false" :class="tab === 'input_tindaklanjut' ? 'bg-bjm-gold/10 text-bjm-gold border-l-4 border-bjm-gold' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm font-medium transition-colors">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Tindak Lanjut Kasus
            </button>
            <button @click="tab = 'tindaklanjut'; sidebarOpen = false" :class="tab === 'tindaklanjut' ? 'bg-bjm-gold/10 text-bjm-gold border-l-4 border-bjm-gold' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm font-medium transition-colors">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Data Tindak Lanjut
            </button>
            <button @click="tab = 'bukti'; sidebarOpen = false" :class="tab === 'bukti' ? 'bg-bjm-gold/10 text-bjm-gold border-l-4 border-bjm-gold' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm font-medium transition-colors">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                Data Bukti
            </button>

            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Laporan & Rekap</p>
            <button @click="tab = 'laporan'; sidebarOpen = false" :class="tab === 'laporan' ? 'bg-bjm-gold/10 text-bjm-gold border-l-4 border-bjm-gold' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm font-medium transition-colors">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Cetak Rekapitulasi
            </button>

        </div>
    </aside>

    <div class="lg:ml-64 flex flex-col min-h-screen relative">
        
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="hidden md:flex items-center bg-slate-100 px-3 py-2 rounded-lg text-slate-500 focus-within:ring-2 focus-within:ring-bjm-gold transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" id="kolomPencarian" placeholder="Ketik untuk mencari data..." class="bg-transparent border-none outline-none text-sm w-48 transition-all duration-300 focus:w-64">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-slate-700">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">Administrator Utama</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-slate-100 hover:bg-red-50 text-slate-500 hover:text-red-500 p-2 rounded-full transition-all duration-300 hover:scale-105" title="Keluar dari Admin">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </header>

        <div class="bg-bjm-dark pt-10 pb-24 px-4 sm:px-6 lg:px-8 border-b-4 border-bjm-gold">
            <div class="flex justify-between items-center">
                <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-white leading-tight">Manajemen Pelanggaran Dan Pelaporan Pegawai</h1>
            </div>
        </div>

        <div class="-mt-16 px-4 sm:px-6 lg:px-8 pb-8">
            
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-md flex items-center gap-3 font-bold text-emerald-700 text-sm transform transition-all">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-md flex items-center gap-3 font-bold text-red-700 text-sm transform transition-all">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-md flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="text-sm text-red-700 font-bold mb-1">Ada kesalahan input:</p>
                        <ul class="list-disc list-inside text-xs text-red-600 space-y-0.5">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-slate-200 p-6 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-sm font-semibold text-slate-500 mb-1">Total Kasus</p>
                            <h3 class="text-3xl font-black text-slate-800">{{ $dataKasus->count() }}</h3>
                        </div>
                        <div class="p-3 bg-slate-100 text-slate-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500">Semua laporan pelanggaran terdaftar.</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-slate-200 p-6 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-sm font-semibold text-slate-500 mb-1">Menunggu Verifikasi</p>
                            <h3 class="text-3xl font-black text-slate-800">{{ $dataKasus->where('status', 'masuk')->count() }}</h3>
                        </div>
                        <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500">Perlu tindakan verifikasi Admin.</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-slate-200 p-6 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-sm font-semibold text-slate-500 mb-1">Proses Audit</p>
                            <h3 class="text-3xl font-black text-slate-800">{{ $dataKasus->where('status', 'investigasi')->count() }}</h3>
                        </div>
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500">Ditangani tim investigator.</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-slate-200 p-6 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-sm font-semibold text-slate-500 mb-1">Kasus Selesai</p>
                            <h3 class="text-3xl font-black text-slate-800">{{ $dataKasus->where('status', 'selesai')->count() }}</h3>
                        </div>
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500">Penyidikan telah ditutup.</p>
                </div>
            </div>

            <div class="mt-8 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                
                <div x-show="tab === 'beranda'" x-transition.opacity>
                    <div class="px-6 py-8 border-b border-slate-200 bg-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-bjm-gold/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
                        <div class="relative z-10">
                            <h2 class="text-2xl font-bold text-slate-800 mb-2">Selamat Datang di Portal Pengawasan 👋</h2>
                            <p class="text-slate-600 mb-8 max-w-2xl">Ini adalah pusat kendali Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai Pemerintah Kota Banjarmasin. Kelola pengaduan, pantau investigasi, dan tindak lanjuti kasus ASN dari satu portal terpadu.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-12">
                                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-8 shadow-sm">
                                    <div class="flex items-center gap-4 mb-6">
                                        <div class="bg-amber-100 p-3 rounded-xl text-amber-600 border border-amber-200">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-slate-800">Komitmen Pemko Banjarmasin</h3>
                                    </div>
                                    <p class="text-slate-600 leading-relaxed italic">
                                        "Sejalan dengan semangat <strong>Banjarmasin Baiman (Barasih wan Nyaman)</strong>, Pemerintah Kota berkomitmen menghadirkan birokrasi yang bersih, profesional, dan berintegritas. Aplikasi Manajemen Pelaporan ini hadir sebagai wujud nyata pengawasan kinerja pegawai dari praktik pelanggaran."
                                    </p>
                                </div>

                                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-8 shadow-sm">
                                    <div class="flex items-center gap-4 mb-6">
                                        <div class="bg-blue-100 p-3 rounded-xl text-blue-600 border border-blue-200">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-slate-800">Standar Pengawasan Internal</h3>
                                    </div>
                                    <ul class="space-y-4 text-slate-600 text-sm">
                                        <li class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span><strong>Anonimitas Terproteksi:</strong> Sistem mengenkripsi identitas pelapor untuk menghindari benturan kepentingan.</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span><strong>Independensi Audit:</strong> Telaah kasus dijalankan secara objektif oleh tim Verifikator dan Investigator.</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span><strong>Transparansi Putusan:</strong> Progres penindakan kasus dapat dipantau oleh pelapor menggunakan kode kasus.</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MENU 0: MASTER DATA PEGAWAI (BARU) -->
                <div x-show="tab === 'master_pegawai'" x-transition.opacity style="display: none;" 
                    x-data="{ showModalMaster: false, editModeMaster: false, formMaster: { id: '', user_id: '', nip: '', nama_pegawai: '', jenis_kelamin: 'Laki-laki', tempat_lahir: '', tanggal_lahir: '', alamat: '', status_kepegawaian: 'PNS', asal_instansi: '', jabatan: '', nomor_hp: '', status_aktif: 'Aktif' } }">
                    <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Master Data Pegawai</h3>
                            <span class="bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold px-3 py-1 rounded-full mt-2 inline-block">Total: {{ count($dataMasterPegawai) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.rekap.cetak', 'master_pegawai') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-sm font-bold shadow-md transition-all transform hover:scale-105">
                                🖨️ Cetak Pegawai
                            </a>
                            <button @click="showModalMaster = true; editModeMaster = false; formMaster = { id: '', user_id: '', nip: '', nama_pegawai: '', jenis_kelamin: 'Laki-laki', tempat_lahir: '', tanggal_lahir: '', alamat: '', status_kepegawaian: 'PNS', asal_instansi: '', jabatan: '', nomor_hp: '', status_aktif: 'Aktif' }" class="bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 text-white text-sm font-bold px-5 py-2.5 rounded-lg flex items-center gap-2 transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Data Pegawai
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="p-4 pl-6">NIP</th>
                                    <th class="p-4">Nama</th>
                                    <th class="p-4">Jenis Kelamin</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4">Instansi</th>
                                    <th class="p-4">Jabatan</th>
                                    <th class="p-4 text-center">Akun Terhubung</th>
                                    <th class="p-4 text-center pr-6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @foreach($dataMasterPegawai as $mp)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-4 pl-6 font-mono text-slate-600">{{ $mp->nip }}</td>
                                    <td class="p-4 font-bold text-slate-800">{{ $mp->nama_pegawai }}</td>
                                    <td class="p-4 text-slate-700">{{ $mp->jenis_kelamin ?? '-' }}</td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-1 rounded text-[10px] uppercase font-bold border {{ $mp->status_kepegawaian == 'PNS' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}">{{ $mp->status_kepegawaian }}</span>
                                    </td>
                                    <td class="p-4 text-xs text-slate-600">{{ $mp->asal_instansi }}</td>
                                    <td class="p-4 text-slate-700">{{ $mp->jabatan }}</td>
                                    <td class="p-4 text-center">
                                        @if($mp->user)
                                            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                                                ✅ {{ $mp->user->name }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full border border-slate-200">
                                                Belum Ada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center pr-6">
                                        <div class="flex items-center justify-center gap-2">
                                            <button @click="showModalMaster = true; editModeMaster = true; formMaster = { 
                                                id: '{{ $mp->id }}', user_id: '{{ $mp->user_id }}', nip: '{{ $mp->nip }}', nama_pegawai: '{{ addslashes($mp->nama_pegawai) }}', jenis_kelamin: '{{ $mp->jenis_kelamin }}', tempat_lahir: '{{ addslashes($mp->tempat_lahir) }}', tanggal_lahir: '{{ $mp->tanggal_lahir }}', alamat: '{{ addslashes(preg_replace('/\r|\n/', ' ', $mp->alamat)) }}', status_kepegawaian: '{{ $mp->status_kepegawaian }}', asal_instansi: '{{ addslashes($mp->asal_instansi) }}', jabatan: '{{ addslashes($mp->jabatan) }}', nomor_hp: '{{ $mp->nomor_hp }}', status_aktif: '{{ $mp->status_aktif }}' 
                                            }" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition-all shadow-sm" title="Edit Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('admin.master_pegawai.destroy', $mp->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus profil data pegawai ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-all shadow-sm" title="Hapus Data">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div x-show="showModalMaster" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4" style="display: none;">
                        <div @click.away="showModalMaster = false" class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden transform transition-all max-h-[90vh] flex flex-col">
                            <div class="bg-bjm-dark p-5 border-b-4 border-bjm-gold flex justify-between items-center sticky top-0 z-10">
                                <h3 class="text-white font-bold text-lg" x-text="editModeMaster ? 'Edit Master Data Pegawai' : 'Tambah Pegawai Baru'"></h3>
                                <button @click="showModalMaster = false" class="text-slate-300 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>
                            <form :action="editModeMaster ? '/admin/master-pegawai/' + formMaster.id : '{{ route('admin.master_pegawai.store') }}'" method="POST" class="p-6 overflow-y-auto">
                                @csrf
                                <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editModeMaster">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">NIP Pegawai <span class="text-red-500">*</span></label>
                                        <input type="text" name="nip" x-model="formMaster.nip" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                        <input type="text" name="nama_pegawai" x-model="formMaster.nama_pegawai" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" x-model="formMaster.jenis_kelamin" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" x-model="formMaster.tempat_lahir" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" x-model="formMaster.tanggal_lahir" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                                        <textarea name="alamat" x-model="formMaster.alamat" rows="2" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Status Kepegawaian <span class="text-red-500">*</span></label>
                                        <select name="status_kepegawaian" x-model="formMaster.status_kepegawaian" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                            <option value="PNS">PNS</option>
                                            <option value="PPPK">PPPK</option>
                                            <option value="CPNS">CPNS</option>
                                            <option value="Honorer">Honorer</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Asal Instansi <span class="text-red-500">*</span></label>
                                        <input type="text" name="asal_instansi" x-model="formMaster.asal_instansi" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Jabatan <span class="text-red-500">*</span></label>
                                        <input type="text" name="jabatan" x-model="formMaster.jabatan" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Nomor HP</label>
                                        <input type="text" name="nomor_hp" x-model="formMaster.nomor_hp" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Akun Login Terhubung (Opsional)</label>
                                        <select name="user_id" x-model="formMaster.user_id" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                            <option value="">-- Tidak Terhubung / Tanpa Akun --</option>
                                            @foreach($dataPegawai as $akun)
                                                <option value="{{ $akun->id }}">{{ $akun->name }} ({{ $akun->peran }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Status Aktif <span class="text-red-500">*</span></label>
                                        <select name="status_aktif" x-model="formMaster.status_aktif" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                            <option value="Aktif">Aktif</option>
                                            <option value="Nonaktif">Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100">
                                    <button type="button" @click="showModalMaster = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 rounded-lg transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">Simpan Profil Pegawai</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- MENU 1: DATA AKSES (LOGIN) -->
                <div x-show="tab === 'pegawai'" x-transition.opacity style="display: none;" 
                    x-data="{ showModal: false, editMode: false, form: { id: '', name: '', email: '', peran: 'investigator' } }">
                    <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Data Akses Pengawas (Login)</h3>
                            <span class="bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold px-3 py-1 rounded-full mt-2 inline-block">Total: {{ count($dataPegawai) }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.rekap.cetak', 'pegawai') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-sm font-bold shadow-md transition-all transform hover:scale-105">
                                🖨️ Cetak Akses
                            </a>
                            <button @click="showModal = true; editMode = false; form = { id: '', name: '', email: '', peran: 'investigator' }" class="bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 text-white text-sm font-bold px-5 py-2.5 rounded-lg flex items-center gap-2 transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Buat Akun Akses
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="p-4 pl-6">Nama Pengguna</th>
                                    <th class="p-4">Email Login</th>
                                    <th class="p-4">Peran Sistem</th>
                                    <th class="p-4 text-center pr-6">Aksi Akses</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @foreach($dataPegawai as $p)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-4 pl-6 font-semibold text-slate-800">{{ $p->name }}</td>
                                    <td class="p-4 text-slate-500 font-medium">{{ $p->email }}</td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">{{ strtoupper(str_replace('_', ' ', $p->peran)) }}</span>
                                    </td>
                                    <td class="p-4 text-center pr-6">
                                        <div class="flex items-center justify-center gap-2">
                                            <button @click="showModal = true; editMode = true; form = { id: '{{ $p->id }}', name: '{{ addslashes($p->name) }}', email: '{{ $p->email }}', peran: '{{ $p->peran }}' }" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-all shadow-sm" title="Edit Akses">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('admin.pegawai.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus akun akses ini? Profil fisiknya (jika ada) tidak akan terhapus.');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-all shadow-sm" title="Hapus Akses">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal Edit Akses -->
                    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4" style="display: none;">
                        <div @click.away="showModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all">
                            <div class="bg-bjm-dark p-5 border-b-4 border-bjm-gold flex justify-between items-center">
                                <h3 class="text-white font-bold text-lg" x-text="editMode ? 'Edit Data Akses' : 'Buat Akun Akses Baru'"></h3>
                                <button @click="showModal = false" class="text-slate-300 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>
                            <form :action="editMode ? '/admin/pegawai/' + form.id : '{{ route('admin.pegawai.store') }}'" method="POST" class="p-6">
                                @csrf
                                <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editMode">
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Nama Pengguna (Display)</label>
                                        <input type="text" name="name" x-model="form.name" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Email Aktif (Untuk Login)</label>
                                        <input type="email" name="email" x-model="form.email" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Peran Akses Sistem</label>
                                        <select name="peran" x-model="form.peran" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                            <option value="investigator">Investigator (Tim Lapangan)</option>
                                            <option value="admin">Administrator</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Kata Sandi <span x-show="editMode" class="text-xs text-slate-400 font-normal">(Kosongkan jika tidak diganti)</span></label>
                                        <input type="password" name="password" :required="!editMode" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none" placeholder="Minimal 8 karakter">
                                    </div>
                                </div>
                                <div class="mt-8 flex justify-end gap-3">
                                    <button type="button" @click="showModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 rounded-lg transition-all transform hover:scale-105 shadow-md shadow-blue-500/20">Simpan Akun</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- MENU 2: PELAPOR -->
                <div x-show="tab === 'pengguna'" x-transition.opacity style="display: none;"
                    x-data="{ showModal: false, editMode: false, form: { id: '', name: '', email: '' } }">
                    <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Data Pelapor Terdaftar</h3>
                            <span class="bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold px-3 py-1 rounded-full mt-2 inline-block">Total: {{ count($dataPengguna) }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.rekap.cetak', 'pengguna') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-sm font-bold shadow-md transition-all transform hover:scale-105">
                                🖨️ Cetak PDF
                            </a>
                            <button @click="showModal = true; editMode = false; form = { id: '', name: '', email: '' }" class="bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 text-white text-sm font-bold px-5 py-2.5 rounded-lg flex items-center gap-2 transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Pelapor
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="p-4 pl-6">Nama Lengkap</th>
                                    <th class="p-4">Email</th>
                                    <th class="p-4">Tgl Mendaftar</th>
                                    <th class="p-4 text-center pr-6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @foreach($dataPengguna as $u)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-4 pl-6 font-semibold text-slate-800">{{ $u->name }}</td>
                                    <td class="p-4 text-slate-500">{{ $u->email }}</td>
                                    <td class="p-4 text-slate-500">{{ $u->created_at->format('d M Y') }}</td>
                                    <td class="p-4 text-center pr-6">
                                        <div class="flex items-center justify-center gap-2">
                                            <button @click="showModal = true; editMode = true; form = { id: '{{ $u->id }}', name: '{{ addslashes($u->name) }}', email: '{{ $u->email }}' }" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition-all shadow-sm" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('admin.pengguna.destroy', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pelapor ini? Laporan miliknya mungkin akan terdampak.');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-all shadow-sm" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4" style="display: none;">
                        <div @click.away="showModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all">
                            <div class="bg-bjm-dark p-5 border-b-4 border-bjm-gold flex justify-between items-center">
                                <h3 class="text-white font-bold text-lg" x-text="editMode ? 'Edit Data Pelapor' : 'Tambah Pelapor Baru'"></h3>
                                <button @click="showModal = false" class="text-slate-300 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>
                            <form :action="editMode ? '/admin/pengguna/' + form.id : '{{ route('admin.pengguna.store') }}'" method="POST" class="p-6">
                                @csrf
                                <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editMode">
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Nama Lengkap</label>
                                        <input type="text" name="name" x-model="form.name" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Email Aktif</label>
                                        <input type="email" name="email" x-model="form.email" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Kata Sandi <span x-show="editMode" class="text-xs text-slate-400 font-normal">(Kosongkan jika tidak diganti)</span></label>
                                        <input type="password" name="password" :required="!editMode" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none" placeholder="Minimal 8 karakter">
                                    </div>
                                </div>
                                <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100">
                                    <button type="button" @click="showModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 rounded-lg transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">Simpan Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- MENU 3: DATA KASUS & INFO TAMBAHAN -->
                <div x-show="tab === 'kasus'" x-transition.opacity style="display: none;"
                    x-data="{ 
                        showModalEditKasus: false, 
                        showModalVerifikasi: false,
                        showModalInfoTambahan: false,
                        formKasus: { 
                            id: '', 
                            kode_tiket: '',
                            user_id: '',
                            nama_pelapor: '',
                            nip: '',
                            nomor_hp: '',
                            email: '',
                            judul_laporan: '', 
                            kategori_laporan: '', 
                            tanggal_kejadian: '', 
                            lokasi_kejadian: '', 
                            status: '', 
                            isi_laporan: '',
                            tingkat_pelanggaran: '',
                            catatan_verifikator: '',
                            alasan_penolakan: '',
                            investigator_id: '',
                            pesan_susulan: '',
                            fakta_lapangan: '',
                            pihak_terlibat: '',
                            kesimpulan: '',
                            tindak_lanjut: '',
                            pihak_penindak: '',
                            tanggal_tindak_lanjut: '',
                            lampiran_bukti_url: '',
                            lampiran_susulan_url: '',
                            bukti_investigasi_url: '',
                            delete_lampiran_bukti: 0,
                            delete_lampiran_susulan: 0
                        },
                        formVerif: { id: '', judul: '', pelapor: '', keputusan: 'terima', tingkat_pelanggaran: '', investigator_id: '', catatan_verifikator: '' },
                        infoTambahan: { id: '', pesan: '', lampiran: null }
                    }">
                    <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Semua Data Laporan Pengaduan</h3>
                        </div>
                        <a href="{{ route('admin.rekap.cetak', 'kasus') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-sm font-bold shadow-md transition-all transform hover:scale-105">
                            🖨️ Cetak Rekap
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="p-4 pl-6">Kode Kasus</th>
                                    <th class="p-4">Pelapor</th>
                                    <th class="p-4 min-w-[200px]">Judul Laporan</th>
                                    <th class="p-4">Tingkat</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4 text-center pr-6">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse($dataKasus as $k)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-4 pl-6 font-mono font-bold text-slate-700">
                                        {{ $k->kode_tiket }}
                                        @if($k->pesan_susulan)
                                            <span class="block mt-1 text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded border border-blue-200 font-bold w-max">
                                                Ada Info Baru
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-slate-700 font-medium">{{ $k->user->name ?? 'Anonim' }}</td>
                                    <td class="p-4 text-slate-600">{{ Str::limit($k->judul_laporan, 30) }}</td>
                                    
                                    <td class="p-4">
                                        @if($k->tingkat_pelanggaran)
                                            <span class="px-2.5 py-1 text-[10px] uppercase rounded font-bold border 
                                                {{ $k->tingkat_pelanggaran == 'Berat' ? 'bg-red-50 text-red-600 border-red-200' : 
                                                ($k->tingkat_pelanggaran == 'Sedang' ? 'bg-amber-50 text-amber-600 border-amber-200' : 
                                                'bg-emerald-50 text-emerald-600 border-emerald-200') }}">
                                                {{ $k->tingkat_pelanggaran }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 italic text-[10px]">-</span>
                                        @endif
                                    </td>

                                    <td class="p-4">
                                        <span class="px-3 py-1 text-[11px] uppercase rounded-full font-bold border 
                                            {{ $k->status == 'selesai' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 
                                                ($k->status == 'investigasi' ? 'bg-blue-50 text-blue-700 border-blue-200' : 
                                                ($k->status == 'ditolak' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-amber-50 text-amber-700 border-amber-200')) }}">
                                            {{ $k->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center pr-6">
                                        <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                            
                                            @if($k->status === 'masuk')
                                                <button @click='showModalVerifikasi = true; formVerif = {
                                                    id: {{ $k->id }},
                                                    judul: {{ json_encode($k->judul_laporan) }},
                                                    pelapor: {{ json_encode($k->user->name ?? "Anonim") }},
                                                    keputusan: "terima",
                                                    tingkat_pelanggaran: "",
                                                    investigator_id: "",
                                                    catatan_verifikator: ""
                                                }' class="inline-flex items-center gap-1 px-3 py-1.5 bg-gradient-to-r from-amber-500 to-bjm-gold hover:from-amber-600 hover:to-amber-700 text-white rounded-lg text-xs font-bold tracking-wide shadow-md shadow-amber-500/20 hover:scale-105 transition-all" title="Verifikasi & Disposisi">
                                                    🛡️ VERIFIKASI
                                                </button>
                                            @endif

                                            <a href="{{ route('admin.show', $k->id) }}" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-all shadow-sm" title="Lihat Detail Berkas">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <button @click='showModalEditKasus = true; formKasus = { 
                                                id: {{ $k->id }}, 
                                                kode_tiket: {{ json_encode($k->kode_tiket) }},
                                                user_id: {{ json_encode($k->user_id) }},
                                                nama_pelapor: {{ json_encode($k->nama_pelapor) }},
                                                nip: {{ json_encode($k->nip) }},
                                                nomor_hp: {{ json_encode($k->nomor_hp) }},
                                                email: {{ json_encode($k->email) }},
                                                judul_laporan: {{ json_encode($k->judul_laporan) }}, 
                                                kategori_laporan: {{ json_encode($k->kategori_laporan) }}, 
                                                tanggal_kejadian: {{ json_encode(\Carbon\Carbon::parse($k->tanggal_kejadian)->format("Y-m-d")) }}, 
                                                lokasi_kejadian: {{ json_encode($k->lokasi_kejadian) }}, 
                                                status: {{ json_encode($k->status) }}, 
                                                isi_laporan: {{ json_encode($k->isi_laporan) }},
                                                tingkat_pelanggaran: {{ json_encode($k->tingkat_pelanggaran) }},
                                                catatan_verifikator: {{ json_encode($k->catatan_verifikator) }},
                                                alasan_penolakan: {{ json_encode($k->alasan_penolakan) }},
                                                investigator_id: {{ json_encode($k->investigator_id) }},
                                                pesan_susulan: {{ json_encode($k->pesan_susulan) }},
                                                fakta_lapangan: {{ json_encode($k->fakta_lapangan) }},
                                                pihak_terlibat: {{ json_encode($k->pihak_terlibat) }},
                                                kesimpulan: {{ json_encode($k->kesimpulan) }},
                                                tindak_lanjut: {{ json_encode($k->tindak_lanjut) }},
                                                pihak_penindak: {{ json_encode($k->pihak_penindak) }},
                                                tanggal_tindak_lanjut: {{ json_encode($k->tanggal_tindak_lanjut ? \Carbon\Carbon::parse($k->tanggal_tindak_lanjut)->format("Y-m-d") : null) }},
                                                lampiran_bukti_url: {{ json_encode($k->lampiran_bukti ? asset("storage/" . $k->lampiran_bukti) : null) }},
                                                lampiran_susulan_url: {{ json_encode($k->lampiran_susulan ? (\Illuminate\Support\Str::startsWith($k->lampiran_susulan, ['bukti_susulan/', 'bukti_pengaduan/']) ? asset("storage/" . $k->lampiran_susulan) : asset("uploads/pengaduan/" . $k->lampiran_susulan)) : null) }},
                                                bukti_investigasi_url: {{ json_encode($k->bukti_investigasi ? asset("storage/" . $k->bukti_investigasi) : null) }},
                                                delete_lampiran_bukti: 0,
                                                delete_lampiran_susulan: 0
                                            }' class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition-all shadow-sm" title="Edit Kasus Manual">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('admin.kasus.destroy', $k->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kasus ini secara permanen?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-all shadow-sm" title="Hapus Permanen">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="p-8 text-center text-slate-500 italic">Belum ada data kasus masuk.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal Verifikasi Kasus -->
                    <div x-show="showModalVerifikasi" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4" style="display: none;" x-transition>
                        <div @click.away="showModalVerifikasi = false" class="bg-white rounded-2xl shadow-xl w-full max-w-xl overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
                            <div class="bg-bjm-dark p-5 border-b-4 border-bjm-gold flex justify-between items-center sticky top-0 z-10">
                                <div class="flex items-center gap-2">
                                    <span class="text-xl">🛡️</span>
                                    <h3 class="text-white font-bold text-lg">Panel Verifikasi & Disposisi Laporan</h3>
                                </div>
                                <button @click="showModalVerifikasi = false" class="text-slate-300 hover:text-white font-bold">✕</button>
                            </div>

                            <form :action="'/admin/kasus/' + formVerif.id + '/verifikasi'" method="POST" class="p-6 overflow-y-auto space-y-4">
                                @csrf
                                @method('PUT')

                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 leading-tight">
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Pelapor: <span class="text-bjm-gold" x-text="formVerif.pelapor"></span></span>
                                    <p class="text-sm font-black text-slate-800 mt-1.5 line-clamp-2" x-text="formVerif.judul"></p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Ambil Keputusan <span class="text-red-500">*</span></label>
                                    <select name="keputusan" x-model="formVerif.keputusan" class="w-full bg-white border-2 border-slate-300 focus:border-bjm-gold rounded-xl px-4 py-3 font-extrabold text-xs outline-none transition">
                                        <option value="terima">🟢 TERIMA & DISPOSISIKAN KE INVESTIGATOR</option>
                                        <option value="tolak">🔴 TOLAK & TUTUP KASUS INI</option>
                                    </select>
                                </div>

                                <div x-show="formVerif.keputusan === 'terima'" x-transition class="space-y-4 pt-2 border-t border-slate-100">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Tingkat Kasus <span class="text-red-500">*</span></label>
                                            <select name="tingkat_pelanggaran" x-model="formVerif.tingkat_pelanggaran" :required="formVerif.keputusan === 'terima'" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-bold focus:border-bjm-gold outline-none">
                                                <option value="">-- Pilih Tingkat --</option>
                                                <option value="Ringan">Ringan (Administrasi/Teguran)</option>
                                                <option value="Sedang">Sedang (Etika/Disiplin)</option>
                                                <option value="Berat">Berat (Pidana/Korupsi/Pungli)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Tugaskan Investigator <span class="text-red-500">*</span></label>
                                            <select name="investigator_id" x-model="formVerif.investigator_id" :required="formVerif.keputusan === 'terima'" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-bold focus:border-bjm-gold outline-none">
                                                <option value="">-- Pilih Investigator --</option>
                                                @foreach($dataPegawai->where('peran', 'investigator') as $inv)
                                                    <option value="{{ $inv->id }}">{{ $inv->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1" x-text="formVerif.keputusan === 'terima' ? 'Instruksi Khusus untuk Investigator' : 'Alasan Laporan Pengaduan Ditolak'"></label>
                                    <textarea name="catatan_verifikator" x-model="formVerif.catatan_verifikator" rows="3" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs focus:border-bjm-gold outline-none" :placeholder="formVerif.keputusan === 'terima' ? 'Contoh: Segera periksa bukti rekaman CCTV di lokasi...' : 'Contoh: Bukti foto buram dan tidak menunjukkan identitas terlapor...'"></textarea>
                                </div>

                                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-100">
                                    <button type="button" @click="showModalVerifikasi = false" class="px-5 py-2.5 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 text-xs font-black text-white rounded-xl shadow-md transition transform hover:scale-105"
                                        :class="formVerif.keputusan === 'terima' ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 shadow-emerald-500/30' : 'bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 shadow-red-500/30'"
                                        x-text="formVerif.keputusan === 'terima' ? 'ACC & DISPOSISI KE INVESTIGATOR' : 'KONFIRMASI TOLAK LAPORAN'">
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Edit Kasus Manual -->
                    <div x-show="showModalEditKasus" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4" style="display: none;" x-transition>
                        <div @click.away="showModalEditKasus = false" class="bg-white rounded-2xl shadow-xl w-full max-w-5xl overflow-hidden transform transition-all max-h-[95vh] flex flex-col">
                            <div class="bg-bjm-dark p-5 border-b-4 border-bjm-gold flex justify-between items-center sticky top-0 z-10">
                                <div class="flex items-center gap-2">
                                    <span class="text-xl">✏️</span>
                                    <h3 class="text-white font-bold text-lg">Koreksi & Edit Seluruh Data Kasus</h3>
                                </div>
                                <button @click="showModalEditKasus = false" class="text-slate-300 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>
                            <form :action="'/admin/kasus/' + formKasus.id" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto space-y-6">
                                @csrf
                                @method('PUT')
                                
                                <!-- SECTION 1: IDENTITAS KASUS & PELAPOR -->
                                <div class="border border-slate-200 rounded-xl p-5 bg-slate-50/50">
                                    <h4 class="text-sm font-bold text-bjm-dark border-b border-slate-200 pb-3 mb-4 flex items-center gap-2">
                                        <span>👤</span> Identitas Kasus & Pelapor
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Kode Tiket / Kasus <span class="text-red-500">*</span></label>
                                            <input type="text" name="kode_tiket" x-model="formKasus.kode_tiket" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Akun Pelapor</label>
                                            <select name="user_id" x-model="formKasus.user_id" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                                <option value="">-- Anonim / Tanpa Akun --</option>
                                                @foreach($dataPengguna as $usr)
                                                    <option value="{{ $usr->id }}">{{ $usr->name }} ({{ $usr->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pelapor (Manual)</label>
                                            <input type="text" name="nama_pelapor" x-model="formKasus.nama_pelapor" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">NIP Pelapor</label>
                                            <input type="text" name="nip" x-model="formKasus.nip" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Nomor HP Pelapor</label>
                                            <input type="text" name="nomor_hp" x-model="formKasus.nomor_hp" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Email Pelapor</label>
                                            <input type="email" name="email" x-model="formKasus.email" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 2: DETAIL LAPORAN UTAMA -->
                                <div class="border border-slate-200 rounded-xl p-5 bg-slate-50/50">
                                    <h4 class="text-sm font-bold text-bjm-dark border-b border-slate-200 pb-3 mb-4 flex items-center gap-2">
                                        <span>📝</span> Detail Laporan Utama
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1">Judul Laporan <span class="text-red-500">*</span></label>
                                            <input type="text" name="judul_laporan" x-model="formKasus.judul_laporan" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1">Kategori Pelanggaran <span class="text-red-500">*</span></label>
                                            <select name="kategori_laporan" x-model="formKasus.kategori_laporan" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                                <option value="">-- Pilih Klasifikasi Terdekat --</option>
                                                <option value="Pungli">Pungli (Pungutan Liar)</option>
                                                <option value="Korupsi">Korupsi</option>
                                                <option value="Gratifikasi">Gratifikasi / Suap</option>
                                                <option value="Pelanggaran Disiplin">Pelanggaran Disiplin ASN</option>
                                                <option value="Benturan Kepentingan">Benturan Kepentingan (Nepotisme)</option>
                                                <option value="Penyalahgunaan Wewenang">Penyalahgunaan Wewenang & Aset</option>
                                                <option value="Lainnya">Lainnya (Tulis manual)</option>
                                            </select>
                                            <div x-show="formKasus.kategori_laporan === 'Lainnya'" x-transition class="mt-3 bg-amber-50 p-4 rounded-xl border border-amber-200 shadow-inner">
                                                <label class="block text-xs font-black text-amber-900 uppercase mb-1.5">
                                                    Tuliskan Jenis Pelanggaran <span class="text-red-600">*</span>
                                                </label>
                                                <input type="text" name="kategori_lainnya" x-model="formKasus.kategori_lainnya" :required="formKasus.kategori_laporan === 'Lainnya'" class="w-full bg-white border border-amber-300 rounded-lg px-4 py-2.5 text-sm font-bold text-slate-800 outline-none focus:ring-2 focus:ring-bjm-gold placeholder:font-normal placeholder:text-slate-400" placeholder="Ketik jenis pelanggaran di sini...">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1">Tanggal Kejadian <span class="text-red-500">*</span></label>
                                            <input type="date" name="tanggal_kejadian" x-model="formKasus.tanggal_kejadian" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1">Lokasi Kejadian <span class="text-red-500">*</span></label>
                                            <input type="text" name="lokasi_kejadian" x-model="formKasus.lokasi_kejadian" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Kronologi / Isi Laporan <span class="text-red-500">*</span></label>
                                        <textarea name="isi_laporan" x-model="formKasus.isi_laporan" rows="6" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Lampiran Bukti Awal (Gambar/PDF)</label>
                                        <input type="hidden" name="delete_lampiran_bukti" x-model="formKasus.delete_lampiran_bukti">
                                        <div class="mt-1 space-y-2">
                                            <template x-if="formKasus.lampiran_bukti_url">
                                                <div class="flex flex-wrap items-center justify-between gap-2 p-2.5 rounded-lg border transition-all" :class="getFileBadgeInfo(formKasus.lampiran_bukti_url).class">
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <span class="text-xs font-semibold">File Saat Ini:</span>
                                                        <a :href="formKasus.lampiran_bukti_url" target="_blank" class="text-xs font-bold hover:underline flex items-center gap-1.5 truncate">
                                                            <span x-text="getFileBadgeInfo(formKasus.lampiran_bukti_url).icon"></span>
                                                            <span x-text="'Lihat Lampiran ' + getFileBadgeInfo(formKasus.lampiran_bukti_url).label"></span>
                                                        </a>
                                                    </div>
                                                    <button type="button" @click="formKasus.lampiran_bukti_url = null; formKasus.delete_lampiran_bukti = 1" class="px-2.5 py-1 text-xs font-semibold text-red-600 hover:text-white bg-white hover:bg-red-600 border border-red-200 hover:border-red-600 rounded-md transition-all shadow-sm shrink-0 flex items-center gap-1.5" title="Hapus File Ini">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        <span>Hapus</span>
                                                    </button>
                                                </div>
                                            </template>
                                            <div>
                                                <input type="file" name="lampiran_bukti" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 transition">
                                                <p x-show="formKasus.lampiran_bukti_url" class="text-[11px] text-slate-500 mt-1 italic">*Pilih file baru jika ingin mengganti lampiran saat ini.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 3: TAHAP VERIFIKASI & DISPOSISI -->
                                <div class="border border-slate-200 rounded-xl p-5 bg-slate-50/50">
                                    <h4 class="text-sm font-bold text-bjm-dark border-b border-slate-200 pb-3 mb-4 flex items-center gap-2">
                                        <span>🛡️</span> Tahap Verifikasi & Disposisi
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Status Kasus <span class="text-red-500">*</span></label>
                                            <select name="status" x-model="formKasus.status" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                                <option value="masuk">Masuk / Menunggu Verifikasi</option>
                                                <option value="investigasi">Proses Audit / Investigasi</option>
                                                <option value="selesai">Selesai</option>
                                                <option value="ditolak">Ditolak</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Tingkat Pelanggaran</label>
                                            <select name="tingkat_pelanggaran" x-model="formKasus.tingkat_pelanggaran" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                                <option value="">-- Belum Ditentukan --</option>
                                                <option value="Ringan">Ringan (Administrasi/Teguran)</option>
                                                <option value="Sedang">Sedang (Etika/Disiplin)</option>
                                                <option value="Berat">Berat (Pidana/Korupsi/Pungli)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Investigator Lapangan</label>
                                            <select name="investigator_id" x-model="formKasus.investigator_id" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                                <option value="">-- Belum Ditugaskan --</option>
                                                @foreach($dataPegawai->where('peran', 'investigator') as $inv)
                                                    <option value="{{ $inv->id }}">{{ $inv->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Verifikator (Instruksi/Disposisi)</label>
                                            <textarea name="catatan_verifikator" x-model="formKasus.catatan_verifikator" rows="4" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Alasan Penolakan (Jika Ditolak)</label>
                                            <textarea name="alasan_penolakan" x-model="formKasus.alasan_penolakan" rows="4" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 4: INFORMASI TAMBAHAN SUSULAN -->
                                <div class="border border-slate-200 rounded-xl p-5 bg-slate-50/50">
                                    <h4 class="text-sm font-bold text-bjm-dark border-b border-slate-200 pb-3 mb-4 flex items-center gap-2">
                                        <span>💬</span> Informasi Tambahan Susulan (Pelapor)
                                    </h4>
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Pesan Susulan / Informasi Tambahan</label>
                                        <textarea name="pesan_susulan" x-model="formKasus.pesan_susulan" rows="4" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Lampiran Susulan (File Bukti Baru)</label>
                                        <input type="hidden" name="delete_lampiran_susulan" x-model="formKasus.delete_lampiran_susulan">
                                        <div class="mt-1 space-y-2">
                                            <template x-if="formKasus.lampiran_susulan_url">
                                                <div class="flex flex-wrap items-center justify-between gap-2 p-2.5 rounded-lg border transition-all" :class="getFileBadgeInfo(formKasus.lampiran_susulan_url).class">
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <span class="text-xs font-semibold">File Saat Ini:</span>
                                                        <a :href="formKasus.lampiran_susulan_url" target="_blank" class="text-xs font-bold hover:underline flex items-center gap-1.5 truncate">
                                                            <span x-text="getFileBadgeInfo(formKasus.lampiran_susulan_url).icon"></span>
                                                            <span x-text="'Lihat Lampiran ' + getFileBadgeInfo(formKasus.lampiran_susulan_url).label"></span>
                                                        </a>
                                                    </div>
                                                    <button type="button" @click="formKasus.lampiran_susulan_url = null; formKasus.delete_lampiran_susulan = 1" class="px-2.5 py-1 text-xs font-semibold text-red-600 hover:text-white bg-white hover:bg-red-600 border border-red-200 hover:border-red-600 rounded-md transition-all shadow-sm shrink-0 flex items-center gap-1.5" title="Hapus File Ini">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        <span>Hapus</span>
                                                    </button>
                                                </div>
                                            </template>
                                            <div>
                                                <input type="file" name="lampiran_susulan" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 transition">
                                                <p x-show="formKasus.lampiran_susulan_url" class="text-[11px] text-slate-500 mt-1 italic">*Pilih file baru jika ingin mengganti lampiran susulan saat ini.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end gap-3 sticky bottom-0 bg-white pt-4 border-t border-slate-200">
                                    <button type="button" @click="showModalEditKasus = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 rounded-lg transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Lihat Info Tambahan (Pengganti Tanggapan) -->
                    <div x-show="showModalInfoTambahan" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4" style="display: none;">
                        <div @click.away="showModalInfoTambahan = false" class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
                            <div class="bg-cyan-600 p-5 flex justify-between items-center sticky top-0 z-10">
                                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                                    💬 Detail Informasi Tambahan
                                </h3>
                                <button @click="showModalInfoTambahan = false" class="text-cyan-100 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>
                            
                            <div class="p-6 overflow-y-auto">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Keterangan dari Pelapor:</label>
                                <div class="bg-slate-50 border border-slate-200 rounded-lg p-5 mb-4 text-slate-700 text-sm italic leading-relaxed">
                                    "<span x-text="infoTambahan.pesan"></span>"
                                </div>

                                <div x-show="infoTambahan.lampiran" class="mb-6">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">File Lampiran:</label>
                                    <a :href="'/storage/' + infoTambahan.lampiran" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white rounded-lg text-sm font-bold border border-blue-200 transition-all shadow-sm">
                                        📎 Unduh / Lihat File Bukti
                                    </a>
                                </div>

                                <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-5">
                                    <form :action="'/admin/info-tambahan/' + infoTambahan.id" method="POST" onsubmit="return confirm('Yakin ingin menghapus informasi ini secara permanen?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-5 py-2.5 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-600 hover:text-white rounded-lg transition-all shadow-sm">Hapus Info Ini</button>
                                    </form>
                                    <button type="button" @click="showModalInfoTambahan = false" class="px-6 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MENU 4: INVESTIGASI -->
                <div x-show="tab === 'investigasi'" x-transition.opacity style="display: none;"
                    x-data="{ showModalEditInvestigasi: false, formInvestigasi: { id: '', fakta_lapangan: '', pihak_terlibat: '', kesimpulan: '', investigator_id: '', bukti_investigasi_url: '', delete_bukti_investigasi: 0 } }">
                    <div class="px-6 py-5 border-b border-slate-200 bg-white flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-800">Data Kertas Kerja Investigasi</h3>
                        <a href="{{ route('admin.rekap.cetak', 'investigasi') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-sm font-bold shadow-md transition-all transform hover:scale-105">
                            🖨️ Cetak Rekap
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="p-4 pl-6">Kode Kasus</th>
                                    <th class="p-4">Investigator Lapangan</th>
                                    <th class="p-4">Fakta Temuan</th>
                                    <th class="p-4 text-center pr-6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse($dataInvestigasi as $i)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-4 pl-6 font-mono font-bold text-slate-600">{{ $i->kode_tiket }}</td>
                                    <td class="p-4 text-slate-800 font-medium">{{ $i->investigator->name ?? 'Tim Lapangan' }}</td>
                                    <td class="p-4 text-slate-600 italic">"{{ Str::limit($i->fakta_lapangan ?? $i->hasil_investigasi, 40) }}"</td>
                                    <td class="p-4 text-center pr-6">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.show', $i->id) }}" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-all shadow-sm" title="Lihat Berkas Lengkap">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <button @click='showModalEditInvestigasi = true; formInvestigasi = { 
                                                id: {{ $i->id }}, 
                                                fakta_lapangan: {{ json_encode($i->fakta_lapangan) }},
                                                pihak_terlibat: {{ json_encode($i->pihak_terlibat) }},
                                                kesimpulan: {{ json_encode($i->kesimpulan) }},
                                                investigator_id: {{ json_encode($i->investigator_id) }},
                                                bukti_investigasi_url: {{ json_encode($i->bukti_investigasi ? asset("storage/" . $i->bukti_investigasi) : null) }},
                                                delete_bukti_investigasi: 0
                                            }' class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition-all shadow-sm" title="Edit Investigasi">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('admin.investigasi.destroy', $i->id) }}" method="POST" class="inline" onsubmit="return confirm('Reset hasil investigasi ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-all shadow-sm" title="Reset">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="p-8 text-center text-slate-500 italic">Belum ada data hasil investigasi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div x-show="showModalEditInvestigasi" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4" style="display: none;">
                        <div @click.away="showModalEditInvestigasi = false" class="bg-white rounded-2xl shadow-xl w-full max-w-4xl overflow-hidden transform transition-all max-h-[92vh] flex flex-col">
                            <div class="bg-bjm-dark p-5 border-b-4 border-bjm-gold flex justify-between items-center sticky top-0 z-10">
                                <h3 class="text-white font-bold text-lg">Edit Kertas Kerja Investigasi</h3>
                                <button @click="showModalEditInvestigasi = false" class="text-slate-300 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>
                            <form :action="'/admin/investigasi/' + formInvestigasi.id" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto">
                                @csrf
                                @method('PUT')
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Investigator Lapangan <span class="text-red-500">*</span></label>
                                        <select name="investigator_id" x-model="formInvestigasi.investigator_id" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                            <option value="">-- Pilih Investigator --</option>
                                            @foreach($dataPegawai->where('peran', 'investigator') as $inv)
                                                <option value="{{ $inv->id }}">{{ $inv->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Fakta di Lapangan <span class="text-red-500">*</span></label>
                                        <textarea name="fakta_lapangan" x-model="formInvestigasi.fakta_lapangan" rows="6" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Pihak Terlibat / Saksi <span class="text-red-500">*</span></label>
                                        <textarea name="pihak_terlibat" x-model="formInvestigasi.pihak_terlibat" rows="4" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Kesimpulan Akhir & Rekomendasi <span class="text-red-500">*</span></label>
                                        <textarea name="kesimpulan" x-model="formInvestigasi.kesimpulan" rows="6" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Lampiran Bukti Temuan Lapangan (Gambar/PDF)</label>
                                        <input type="hidden" name="delete_bukti_investigasi" x-model="formInvestigasi.delete_bukti_investigasi">
                                        <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center mt-1">
                                            <input type="file" name="bukti_investigasi" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 transition">
                                            <template x-if="formInvestigasi.bukti_investigasi_url">
                                                <div class="flex items-center gap-2">
                                                    <a :href="formInvestigasi.bukti_investigasi_url" target="_blank" :class="getFileBadgeInfo(formInvestigasi.bukti_investigasi_url).class" class="text-xs font-bold shrink-0 px-3 py-2 rounded-lg border flex items-center gap-1.5 shadow-sm transition-all hover:underline">
                                                        <span x-text="getFileBadgeInfo(formInvestigasi.bukti_investigasi_url).icon"></span>
                                                        <span x-text="'Lihat Berkas ' + getFileBadgeInfo(formInvestigasi.bukti_investigasi_url).label + ' Saat Ini'"></span>
                                                    </a>
                                                    <button type="button" @click="formInvestigasi.bukti_investigasi_url = null; formInvestigasi.delete_bukti_investigasi = 1" class="p-1.5 text-red-600 hover:text-white bg-red-50 hover:bg-red-500 border border-red-200 rounded-lg transition-all shadow-sm shrink-0" title="Hapus File Ini">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-4">
                                    <button type="button" @click="showModalEditInvestigasi = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 rounded-lg transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">Simpan Koreksi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- MENU 5: TINDAK LANJUT -->
                <div x-show="tab === 'tindaklanjut'" x-transition.opacity style="display: none;"
                    x-data="{ showModalEditTindakLanjut: false, formTindakLanjut: { id: '', pihak_penindak: '', tanggal_tindak_lanjut: '', tindak_lanjut: '' } }">
                    <div class="px-6 py-5 border-b border-slate-200 bg-white shadow-sm flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Arsip Keputusan & Tindak Lanjut</h3>
                            <p class="text-xs text-slate-500">Daftar kasus pegawai yang telah selesai diproses eksekusi keputusannya.</p>
                        </div>
                        <a href="{{ route('admin.rekap.cetak', 'tindaklanjut') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-sm font-bold shadow-md transition-all transform hover:scale-105">
                            🖨️ Cetak Rekap
                        </a>
                    </div>
                    <div class="overflow-x-auto mt-4">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="p-4 pl-6">Kode Kasus</th>
                                    <th class="p-4 min-w-[200px]">Judul Laporan</th>
                                    <th class="p-4">Tingkat</th>
                                    <th class="p-4">Instansi Penindak</th>
                                    <th class="p-4">Tanggal Eksekusi</th>
                                    <th class="p-4 text-center pr-6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse($dataTindakLanjut as $dt)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-4 pl-6 font-mono font-bold text-slate-700">{{ $dt->kode_tiket }}</td>
                                    <td class="p-4 text-slate-600">{{ Str::limit($dt->judul_laporan, 40) }}</td>
                                    <td class="p-4">
                                        @if($dt->tingkat_pelanggaran)
                                            <span class="px-2.5 py-1 text-[10px] uppercase rounded font-bold border 
                                                {{ $dt->tingkat_pelanggaran == 'Berat' ? 'bg-red-50 text-red-600 border-red-200' : 
                                                ($dt->tingkat_pelanggaran == 'Sedang' ? 'bg-amber-50 text-amber-600 border-amber-200' : 
                                                'bg-emerald-50 text-emerald-600 border-emerald-200') }}">
                                                {{ $dt->tingkat_pelanggaran }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 italic text-[10px]">-</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-slate-700 font-medium">{{ $dt->pihak_penindak ?? '-' }}</td>
                                    <td class="p-4 text-slate-600">
                                        {{ $dt->tanggal_tindak_lanjut ? \Carbon\Carbon::parse($dt->tanggal_tindak_lanjut)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="p-4 text-center pr-6">
                                        <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                            <a href="{{ route('admin.show', $dt->id) }}" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-all shadow-sm" title="Lihat Detail Berkas">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <button @click='showModalEditTindakLanjut = true; formTindakLanjut = { 
                                                id: {{ $dt->id }}, 
                                                judul_laporan: {{ json_encode($dt->judul_laporan) }},
                                                kategori_laporan: {{ json_encode($dt->kategori_laporan) }},
                                                tanggal_kejadian: "{{ $dt->tanggal_kejadian ? \Carbon\Carbon::parse($dt->tanggal_kejadian)->format('Y-m-d') : '' }}",
                                                lokasi_kejadian: {{ json_encode($dt->lokasi_kejadian) }},
                                                isi_laporan: {{ json_encode($dt->isi_laporan) }},
                                                tingkat_pelanggaran: {{ json_encode($dt->tingkat_pelanggaran) }},
                                                investigator_id: {{ json_encode($dt->investigator_id) }},
                                                fakta_lapangan: {{ json_encode($dt->fakta_lapangan) }},
                                                pihak_terlibat: {{ json_encode($dt->pihak_terlibat) }},
                                                kesimpulan: {{ json_encode($dt->kesimpulan) }},
                                                pihak_penindak: {{ json_encode($dt->pihak_penindak) }},
                                                tanggal_tindak_lanjut: "{{ $dt->tanggal_tindak_lanjut ? \Carbon\Carbon::parse($dt->tanggal_tindak_lanjut)->format('Y-m-d') : '' }}",
                                                tindak_lanjut: {{ json_encode($dt->tindak_lanjut) }},
                                                lampiran_bukti_url: {{ json_encode($dt->lampiran_bukti ? asset('storage/' . $dt->lampiran_bukti) : null) }},
                                                lampiran_susulan_url: {{ json_encode($dt->lampiran_susulan ? (\Illuminate\Support\Str::startsWith($dt->lampiran_susulan, ['bukti_susulan/', 'bukti_pengaduan/']) ? asset('storage/' . $dt->lampiran_susulan) : asset('uploads/pengaduan/' . $dt->lampiran_susulan)) : null) }},
                                                bukti_investigasi_url: {{ json_encode($dt->bukti_investigasi ? asset('storage/' . $dt->bukti_investigasi) : null) }},
                                                delete_lampiran_bukti: 0,
                                                delete_lampiran_susulan: 0,
                                                delete_bukti_investigasi: 0
                                            }' class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition-all shadow-sm" title="Edit Keputusan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('admin.tindaklanjut.destroy', $dt->id) }}" method="POST" class="inline" onsubmit="return confirm('Batalkan keputusan ini? Status kasus akan kembali ke tahap Investigasi.');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-all shadow-sm" title="Batalkan Keputusan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="p-12 text-center text-slate-500 italic bg-slate-50 rounded-xl border border-dashed border-slate-300">Belum ada data tindak lanjut yang diinput.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div x-show="showModalEditTindakLanjut" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4" style="display: none;">
                        <div @click.away="showModalEditTindakLanjut = false" class="bg-white rounded-2xl shadow-xl w-full max-w-4xl overflow-hidden transform transition-all flex flex-col max-h-[92vh]">
                            <div class="bg-bjm-dark p-5 border-b-4 border-bjm-gold flex justify-between items-center sticky top-0 z-10">
                                <h3 class="text-white font-bold text-lg">Koreksi Data Keputusan Tindak Lanjut</h3>
                                <button @click="showModalEditTindakLanjut = false" class="text-slate-300 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>
                            <form :action="'/admin/tindaklanjut/' + formTindakLanjut.id" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto">
                                @csrf
                                @method('PUT')
                                
                                <div class="bg-slate-50 border border-slate-200 p-4 rounded-xl mb-4">
                                    <h4 class="text-xs font-bold text-slate-500 uppercase mb-3 border-b border-slate-200 pb-2">1. Data Kasus Laporan</h4>
                                    <div class="mb-3">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Laporan</label>
                                        <input type="text" name="judul_laporan" x-model="formTindakLanjut.judul_laporan" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                                            <select name="kategori_laporan" x-model="formTindakLanjut.kategori_laporan" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                                <option value="">-- Pilih Klasifikasi Terdekat --</option>
                                                <option value="Pungli">Pungli (Pungutan Liar)</option>
                                                <option value="Korupsi">Korupsi</option>
                                                <option value="Gratifikasi">Gratifikasi / Suap</option>
                                                <option value="Pelanggaran Disiplin">Pelanggaran Disiplin ASN</option>
                                                <option value="Benturan Kepentingan">Benturan Kepentingan (Nepotisme)</option>
                                                <option value="Penyalahgunaan Wewenang">Penyalahgunaan Wewenang & Aset</option>
                                                <option value="Lainnya">Lainnya (Tulis manual)</option>
                                            </select>
                                            <div x-show="formTindakLanjut.kategori_laporan === 'Lainnya'" x-transition class="mt-3 bg-amber-50 p-4 rounded-xl border border-amber-200 shadow-inner">
                                                <label class="block text-xs font-black text-amber-900 uppercase mb-1.5">
                                                    Tuliskan Jenis Pelanggaran <span class="text-red-600">*</span>
                                                </label>
                                                <input type="text" name="kategori_lainnya" x-model="formTindakLanjut.kategori_lainnya" :required="formTindakLanjut.kategori_laporan === 'Lainnya'" class="w-full bg-white border border-amber-300 rounded-lg px-4 py-2.5 text-sm font-bold text-slate-800 outline-none focus:ring-2 focus:ring-bjm-gold placeholder:font-normal placeholder:text-slate-400" placeholder="Ketik jenis pelanggaran di sini...">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Tgl Kejadian</label>
                                            <input type="date" name="tanggal_kejadian" x-model="formTindakLanjut.tanggal_kejadian" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Tingkat Pelanggaran</label>
                                            <select name="tingkat_pelanggaran" x-model="formTindakLanjut.tingkat_pelanggaran" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                                <option value="">-- Kosong --</option>
                                                <option value="Ringan">Ringan</option>
                                                <option value="Sedang">Sedang</option>
                                                <option value="Berat">Berat</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Kejadian</label>
                                        <input type="text" name="lokasi_kejadian" x-model="formTindakLanjut.lokasi_kejadian" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Kronologi Kejadian</label>
                                        <textarea name="isi_laporan" x-model="formTindakLanjut.isi_laporan" rows="3" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none"></textarea>
                                    </div>
                                </div>

                                <div class="bg-blue-50/50 border border-blue-100 p-4 rounded-xl mb-4">
                                    <h4 class="text-xs font-bold text-blue-600 uppercase mb-3 border-b border-blue-200 pb-2">2. Hasil Investigasi</h4>
                                    <div class="mb-3">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Investigator Terkait</label>
                                        <select name="investigator_id" x-model="formTindakLanjut.investigator_id" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                            <option value="">-- Pilih Investigator --</option>
                                            @foreach($dataPegawai->where('peran', 'investigator') as $inv)
                                                <option value="{{ $inv->id }}">{{ $inv->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Fakta Lapangan</label>
                                            <textarea name="fakta_lapangan" x-model="formTindakLanjut.fakta_lapangan" rows="4" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Pihak Terlibat</label>
                                            <textarea name="pihak_terlibat" x-model="formTindakLanjut.pihak_terlibat" rows="4" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none"></textarea>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Kesimpulan Tim Investigasi</label>
                                        <textarea name="kesimpulan" x-model="formTindakLanjut.kesimpulan" rows="4" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none"></textarea>
                                    </div>
                                </div>

                                <div class="bg-emerald-50/50 border border-emerald-100 p-4 rounded-xl mb-2">
                                    <h4 class="text-xs font-bold text-emerald-600 uppercase mb-3 border-b border-emerald-200 pb-2">3. Keputusan & Tindak Lanjut Eksekusi</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Pihak Penindak / Instansi</label>
                                            <input type="text" name="pihak_penindak" x-model="formTindakLanjut.pihak_penindak" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Eksekusi</label>
                                            <input type="date" name="tanggal_tindak_lanjut" x-model="formTindakLanjut.tanggal_tindak_lanjut" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Detail Keputusan & Sanksi</label>
                                        <textarea name="tindak_lanjut" x-model="formTindakLanjut.tindak_lanjut" rows="6" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none"></textarea>
                                    </div>
                                </div>

                                <div class="bg-purple-50/50 border border-purple-100 p-4 rounded-xl mb-2">
                                    <h4 class="text-xs font-bold text-purple-600 uppercase mb-3 border-b border-purple-200 pb-2">4. Pembaruan File Bukti</h4>
                                    <div class="mb-3 text-xs text-purple-700 bg-purple-100 p-2 rounded text-center font-medium">Kosongkan jika tidak ada file yang ingin diganti.</div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="mb-3">
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Bukti Awal Pelapor</label>
                                            <input type="hidden" name="delete_lampiran_bukti" x-model="formTindakLanjut.delete_lampiran_bukti">
                                            <template x-if="formTindakLanjut.lampiran_bukti_url">
                                                <div class="mb-2 text-[10px] flex items-center gap-2">
                                                    <span class="text-slate-500">Saat ini:</span>
                                                    <a :href="formTindakLanjut.lampiran_bukti_url" target="_blank" :class="getFileBadgeInfo(formTindakLanjut.lampiran_bukti_url).class" class="font-bold hover:underline px-2.5 py-1 rounded border inline-flex items-center gap-1.5">
                                                        <span x-text="getFileBadgeInfo(formTindakLanjut.lampiran_bukti_url).icon"></span>
                                                        <span x-text="'Lihat Berkas ' + getFileBadgeInfo(formTindakLanjut.lampiran_bukti_url).label"></span>
                                                    </a>
                                                    <button type="button" @click="formTindakLanjut.lampiran_bukti_url = null; formTindakLanjut.delete_lampiran_bukti = 1" class="p-1 text-red-600 hover:text-white bg-red-50 hover:bg-red-500 border border-red-200 rounded transition-all shadow-sm shrink-0" title="Hapus File Ini">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </div>
                                            </template>
                                            <input type="file" name="lampiran_bukti" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200">
                                        </div>
                                        <div class="mb-3">
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Bukti Tambahan</label>
                                            <input type="hidden" name="delete_lampiran_susulan" x-model="formTindakLanjut.delete_lampiran_susulan">
                                            <template x-if="formTindakLanjut.lampiran_susulan_url">
                                                <div class="mb-2 text-[10px] flex items-center gap-2">
                                                    <span class="text-slate-500">Saat ini:</span>
                                                    <a :href="formTindakLanjut.lampiran_susulan_url" target="_blank" :class="getFileBadgeInfo(formTindakLanjut.lampiran_susulan_url).class" class="font-bold hover:underline px-2.5 py-1 rounded border inline-flex items-center gap-1.5">
                                                        <span x-text="getFileBadgeInfo(formTindakLanjut.lampiran_susulan_url).icon"></span>
                                                        <span x-text="'Lihat Berkas ' + getFileBadgeInfo(formTindakLanjut.lampiran_susulan_url).label"></span>
                                                    </a>
                                                    <button type="button" @click="formTindakLanjut.lampiran_susulan_url = null; formTindakLanjut.delete_lampiran_susulan = 1" class="p-1 text-red-600 hover:text-white bg-red-50 hover:bg-red-500 border border-red-200 rounded transition-all shadow-sm shrink-0" title="Hapus File Ini">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </div>
                                            </template>
                                            <input type="file" name="lampiran_susulan" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200">
                                        </div>
                                        <div class="md:col-span-2 mb-2">
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Bukti Temuan Investigasi</label>
                                            <input type="hidden" name="delete_bukti_investigasi" x-model="formTindakLanjut.delete_bukti_investigasi">
                                            <template x-if="formTindakLanjut.bukti_investigasi_url">
                                                <div class="mb-2 text-[10px] flex items-center gap-2">
                                                    <span class="text-slate-500">Saat ini:</span>
                                                    <a :href="formTindakLanjut.bukti_investigasi_url" target="_blank" :class="getFileBadgeInfo(formTindakLanjut.bukti_investigasi_url).class" class="font-bold hover:underline px-2.5 py-1 rounded border inline-flex items-center gap-1.5">
                                                        <span x-text="getFileBadgeInfo(formTindakLanjut.bukti_investigasi_url).icon"></span>
                                                        <span x-text="'Lihat Berkas ' + getFileBadgeInfo(formTindakLanjut.bukti_investigasi_url).label"></span>
                                                    </a>
                                                    <button type="button" @click="formTindakLanjut.bukti_investigasi_url = null; formTindakLanjut.delete_bukti_investigasi = 1" class="p-1 text-red-600 hover:text-white bg-red-50 hover:bg-red-500 border border-red-200 rounded transition-all shadow-sm shrink-0" title="Hapus File Ini">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </div>
                                            </template>
                                            <input type="file" name="bukti_investigasi" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-4">
                                    <button type="button" @click="showModalEditTindakLanjut = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 rounded-lg transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">Perbarui Keputusan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- MENU 6: BUKTI -->
                <div x-show="tab === 'bukti'" x-transition.opacity style="display: none;"
                    x-data="{ showModalEditBukti: false, formBukti: { id: '', kode_tiket: '', lampiran_bukti_url: '', lampiran_susulan_url: '', bukti_investigasi_url: '', delete_lampiran_bukti: 0, delete_lampiran_susulan: 0, delete_bukti_investigasi: 0 } }">
                    <div class="px-6 py-5 border-b border-slate-200 bg-white shadow-sm flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Manajemen Data Bukti</h3>
                            <p class="text-xs text-slate-500">Pusat kontrol file fisik temuan pelanggaran pegawai.</p>
                        </div>
                        <a href="{{ route('admin.rekap.cetak', 'bukti') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-sm font-bold shadow-md transition-all transform hover:scale-105">
                            🖨️ Cetak Rekap
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="p-4 pl-6">Kode Kasus</th>
                                    <th class="p-4">Bukti Awal</th>
                                    <th class="p-4">Bukti Tambahan</th>
                                    <th class="p-4">Bukti Investigasi</th>
                                    <th class="p-4 text-center pr-6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse($dataBukti as $db)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-4 pl-6 font-mono font-bold text-slate-700">{{ $db->kode_tiket }}</td>
                                    <td class="p-4">
                                        @if($db->lampiran_bukti)
                                            @php
                                                $extBukti = strtolower(pathinfo($db->lampiran_bukti, PATHINFO_EXTENSION));
                                            @endphp
                                            @if($extBukti === 'pdf')
                                                <a href="{{ asset('storage/' . $db->lampiran_bukti) }}" target="_blank" class="text-xs font-bold text-rose-600 hover:underline inline-flex items-center gap-1 bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-200" title="Dokumen PDF">📄 PDF</a>
                                            @elseif(in_array($extBukti, ['doc', 'docx']))
                                                <a href="{{ asset('storage/' . $db->lampiran_bukti) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline inline-flex items-center gap-1 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-200" title="Dokumen Word">📝 Word</a>
                                            @elseif(in_array($extBukti, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                <a href="{{ asset('storage/' . $db->lampiran_bukti) }}" target="_blank" class="text-xs font-bold text-emerald-600 hover:underline inline-flex items-center gap-1 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200" title="Gambar / Foto">🖼️ Foto</a>
                                            @else
                                                <a href="{{ asset('storage/' . $db->lampiran_bukti) }}" target="_blank" class="text-xs font-bold text-slate-600 hover:underline inline-flex items-center gap-1 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200" title="Berkas File">📁 File</a>
                                            @endif
                                        @else <span class="text-slate-400 italic text-xs">-</span> @endif
                                    </td>
                                    <td class="p-4">
                                        @if($db->lampiran_susulan)
                                            @php
                                                $extSusulan = strtolower(pathinfo($db->lampiran_susulan, PATHINFO_EXTENSION));
                                                $urlSusulan = \Illuminate\Support\Str::startsWith($db->lampiran_susulan, ['bukti_susulan/', 'bukti_pengaduan/']) ? asset('storage/' . $db->lampiran_susulan) : asset('uploads/pengaduan/' . $db->lampiran_susulan);
                                            @endphp
                                            @if($extSusulan === 'pdf')
                                                <a href="{{ $urlSusulan }}" target="_blank" class="text-xs font-bold text-rose-600 hover:underline inline-flex items-center gap-1 bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-200" title="Dokumen PDF">📄 PDF</a>
                                            @elseif(in_array($extSusulan, ['doc', 'docx']))
                                                <a href="{{ $urlSusulan }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline inline-flex items-center gap-1 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-200" title="Dokumen Word">📝 Word</a>
                                            @elseif(in_array($extSusulan, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                <a href="{{ $urlSusulan }}" target="_blank" class="text-xs font-bold text-amber-600 hover:underline inline-flex items-center gap-1 bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200" title="Gambar / Foto">🖼️ Foto</a>
                                            @else
                                                <a href="{{ $urlSusulan }}" target="_blank" class="text-xs font-bold text-slate-600 hover:underline inline-flex items-center gap-1 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200" title="Berkas File">📁 File</a>
                                            @endif
                                        @else <span class="text-slate-400 italic text-xs">-</span> @endif
                                    </td>
                                    <td class="p-4">
                                        @if($db->bukti_investigasi)
                                            @php
                                                $extInv = strtolower(pathinfo($db->bukti_investigasi, PATHINFO_EXTENSION));
                                            @endphp
                                            @if($extInv === 'pdf')
                                                <a href="{{ asset('storage/' . $db->bukti_investigasi) }}" target="_blank" class="text-xs font-bold text-rose-600 hover:underline inline-flex items-center gap-1 bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-200" title="Dokumen PDF">📄 PDF</a>
                                            @elseif(in_array($extInv, ['doc', 'docx']))
                                                <a href="{{ asset('storage/' . $db->bukti_investigasi) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline inline-flex items-center gap-1 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-200" title="Dokumen Word">📝 Word</a>
                                            @elseif(in_array($extInv, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                <a href="{{ asset('storage/' . $db->bukti_investigasi) }}" target="_blank" class="text-xs font-bold text-purple-600 hover:underline inline-flex items-center gap-1 bg-purple-50 px-3 py-1.5 rounded-lg border border-purple-200" title="Gambar / Foto">📷 Foto</a>
                                            @else
                                                <a href="{{ asset('storage/' . $db->bukti_investigasi) }}" target="_blank" class="text-xs font-bold text-slate-600 hover:underline inline-flex items-center gap-1 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200" title="Berkas File">📁 File</a>
                                            @endif
                                        @else <span class="text-slate-400 italic text-xs">-</span> @endif
                                    </td>
                                    <td class="p-4 text-center pr-6 flex justify-center items-center gap-2">
                                        <button @click="showModalEditBukti = true; formBukti = { 
                                            id: {{ $db->id }}, 
                                            kode_tiket: '{{ $db->kode_tiket }}',
                                            lampiran_bukti_url: {{ json_encode($db->lampiran_bukti ? asset('storage/' . $db->lampiran_bukti) : null) }},
                                            lampiran_susulan_url: {{ json_encode($db->lampiran_susulan ? (\Illuminate\Support\Str::startsWith($db->lampiran_susulan, ['bukti_susulan/', 'bukti_pengaduan/']) ? asset('storage/' . $db->lampiran_susulan) : asset('uploads/pengaduan/' . $db->lampiran_susulan)) : null) }},
                                            bukti_investigasi_url: {{ json_encode($db->bukti_investigasi ? asset('storage/' . $db->bukti_investigasi) : null) }},
                                            delete_lampiran_bukti: 0,
                                            delete_lampiran_susulan: 0,
                                            delete_bukti_investigasi: 0
                                        }" class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-800 text-slate-600 hover:text-white border border-slate-300 hover:border-slate-800 px-3 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm" title="Kelola Berkas">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            Kelola
                                        </button>
                                        
                                        <form action="{{ route('admin.bukti.destroy', $db->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh berkas bukti pada kasus #{{ $db->kode_tiket }}? Tindakan ini permanen.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 hover:border-red-600 px-3 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm" title="Hapus Seluruh Berkas">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="p-8 text-center text-slate-500 italic">Tidak ada berkas bukti yang terlampir.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
 
                    <div x-show="showModalEditBukti" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4" style="display: none;">
                        <div @click.away="showModalEditBukti = false" class="bg-white rounded-2xl shadow-xl w-full max-w-xl overflow-hidden transform transition-all">
                            <div class="bg-bjm-dark p-5 border-b-4 border-bjm-gold flex justify-between items-center">
                                <h3 class="text-white font-bold text-lg">Perbarui Berkas Bukti</h3>
                                <button @click="showModalEditBukti = false" class="text-slate-300 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>
                            <form :action="'/admin/bukti/' + formBukti.id" method="POST" enctype="multipart/form-data" class="p-6">
                                @csrf
                                @method('PUT')
                                
                                <div class="bg-blue-50 border border-blue-200 p-3 rounded-lg mb-4 text-xs text-blue-700">
                                    File lama akan tertimpa otomatis oleh unggahan baru Anda. Kosongkan jika tidak ingin mengubah.
                                </div>
 
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Bukti Awal Pelapor</label>
                                    <input type="hidden" name="delete_lampiran_bukti" x-model="formBukti.delete_lampiran_bukti">
                                    <template x-if="formBukti.lampiran_bukti_url">
                                        <div class="mb-2 text-xs flex items-center gap-2">
                                            <span class="text-slate-500">Berkas saat ini:</span>
                                            <a :href="formBukti.lampiran_bukti_url" target="_blank" :class="getFileBadgeInfo(formBukti.lampiran_bukti_url).class" class="font-bold hover:underline px-3 py-1 rounded border inline-flex items-center gap-1.5 shadow-sm">
                                                <span x-text="getFileBadgeInfo(formBukti.lampiran_bukti_url).icon"></span>
                                                <span x-text="'Lihat Berkas ' + getFileBadgeInfo(formBukti.lampiran_bukti_url).label"></span>
                                            </a>
                                            <button type="button" @click="formBukti.lampiran_bukti_url = null; formBukti.delete_lampiran_bukti = 1" class="p-1 text-red-600 hover:text-white bg-red-50 hover:bg-red-500 border border-red-200 rounded transition-all shadow-sm shrink-0" title="Hapus File Ini">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </template>
                                    <input type="file" name="lampiran_bukti" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                                </div>
 
                                <div class="mb-4 pt-4 border-t border-slate-100">
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Bukti Tambahan Pelapor / Admin</label>
                                    <input type="hidden" name="delete_lampiran_susulan" x-model="formBukti.delete_lampiran_susulan">
                                    <template x-if="formBukti.lampiran_susulan_url">
                                        <div class="mb-2 text-xs flex items-center gap-2">
                                            <span class="text-slate-500">Berkas saat ini:</span>
                                            <a :href="formBukti.lampiran_susulan_url" target="_blank" :class="getFileBadgeInfo(formBukti.lampiran_susulan_url).class" class="font-bold hover:underline px-3 py-1 rounded border inline-flex items-center gap-1.5 shadow-sm">
                                                <span x-text="getFileBadgeInfo(formBukti.lampiran_susulan_url).icon"></span>
                                                <span x-text="'Lihat Berkas ' + getFileBadgeInfo(formBukti.lampiran_susulan_url).label"></span>
                                            </a>
                                            <button type="button" @click="formBukti.lampiran_susulan_url = null; formBukti.delete_lampiran_susulan = 1" class="p-1 text-red-600 hover:text-white bg-red-50 hover:bg-red-500 border border-red-200 rounded transition-all shadow-sm shrink-0" title="Hapus File Ini">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </template>
                                    <input type="file" name="lampiran_susulan" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition">
                                </div>
 
                                <div class="mb-4 pt-4 border-t border-slate-100">
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Bukti Temuan Investigasi</label>
                                    <input type="hidden" name="delete_bukti_investigasi" x-model="formBukti.delete_bukti_investigasi">
                                    <template x-if="formBukti.bukti_investigasi_url">
                                        <div class="mb-2 text-xs flex items-center gap-2">
                                            <span class="text-slate-500">Berkas saat ini:</span>
                                            <a :href="formBukti.bukti_investigasi_url" target="_blank" :class="getFileBadgeInfo(formBukti.bukti_investigasi_url).class" class="font-bold hover:underline px-3 py-1 rounded border inline-flex items-center gap-1.5 shadow-sm">
                                                <span x-text="getFileBadgeInfo(formBukti.bukti_investigasi_url).icon"></span>
                                                <span x-text="'Lihat Berkas ' + getFileBadgeInfo(formBukti.bukti_investigasi_url).label"></span>
                                            </a>
                                            <button type="button" @click="formBukti.bukti_investigasi_url = null; formBukti.delete_bukti_investigasi = 1" class="p-1 text-red-600 hover:text-white bg-red-50 hover:bg-red-500 border border-red-200 rounded transition-all shadow-sm shrink-0" title="Hapus File Ini">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </template>
                                    <input type="file" name="bukti_investigasi" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition">
                                </div>
 
                                <div class="mt-8 flex justify-end gap-3">
                                    <button type="button" @click="showModalEditBukti = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 rounded-lg transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">Simpan Berkas</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- MENU 7: EKSEKUSI / INPUT TINDAK LANJUT -->
                <div x-show="tab === 'input_tindaklanjut'" x-transition.opacity style="display: none;">
                    <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Antrean Penjatuhan Keputusan Kasus</h3>
                            <p class="text-xs text-slate-500 mt-1">Daftar kasus pegawai yang telah selesai diselidiki dan menunggu putusan sanksi akhir.</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="p-4 pl-6">Kode Kasus</th>
                                    <th class="p-4">Kesimpulan Hasil Audit</th>
                                    <th class="p-4 text-center pr-6">Aksi Penindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse($kasusPerluTindakLanjut as $kpt)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-4 pl-6 font-mono font-bold text-amber-600">{{ $kpt->kode_tiket }}</td>
                                    <td class="p-4 text-slate-600 italic">"{{ Str::limit($kpt->kesimpulan, 80) }}"</td>
                                    <td class="p-4 text-center pr-6">
                                        <a href="{{ route('admin.tindaklanjut.edit', $kpt->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-bjm-dark hover:bg-slate-800 text-white text-xs font-bold rounded-lg transition-all shadow-md transform hover:scale-105">
                                            ⚖️ Ketok Putusan Final
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="p-8 text-center text-slate-500 italic">Antrean penindakan kasus bersih.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- MENU 8: LAPORAN & REKAP (CETAK REKAPITULASI) -->
                <div x-show="tab === 'laporan'" x-transition.opacity style="display: none;">
                    <div class="px-6 py-5 border-b border-slate-200 bg-white">
                        <h3 class="text-lg font-bold text-slate-800">Cetak Rekapitulasi Laporan</h3>
                        <p class="text-xs text-slate-500 mt-1">Unduh atau cetak berkas rekapitulasi data sistem secara terpusat.</p>
                    </div>
                    
                    <div class="p-6 bg-slate-50/50 space-y-8">
                        
                        <!-- SECTION: MASTER DATA -->
                        <div>
                            <div class="flex items-center gap-4 mb-5">
                                <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Cetak Master Data</h4>
                                <div class="flex-1 h-px bg-slate-300"></div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Card: Data Akses -->
                                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="p-2.5 bg-slate-100 text-slate-700 rounded-lg text-lg">
                                                🔑
                                            </div>
                                            <h4 class="font-bold text-slate-800 text-sm">Data Akses Pengawas</h4>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-relaxed mb-5">Daftar data akun login internal untuk Administrator dan Investigator sistem.</p>
                                    </div>
                                    <a href="{{ route('admin.rekap.cetak', 'pegawai') }}" target="_blank" class="w-full text-center inline-flex justify-center items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                        🖨️ Cetak Rekap Akses
                                    </a>
                                </div>

                                <!-- Card: Data Pelapor -->
                                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="p-2.5 bg-orange-50 text-orange-600 rounded-lg text-lg">
                                                👤
                                            </div>
                                            <h4 class="font-bold text-slate-800 text-sm">Data Pelapor</h4>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-relaxed mb-5">Daftar masyarakat atau ASN pelapor terdaftar yang melaporkan aduan pelanggaran.</p>
                                    </div>
                                    <a href="{{ route('admin.rekap.cetak', 'pengguna') }}" target="_blank" class="w-full text-center inline-flex justify-center items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                        🖨️ Cetak Rekap Pelapor
                                    </a>
                                </div>

                                <!-- Card: Data Pegawai -->
                                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="p-2.5 bg-amber-50 text-amber-600 rounded-lg text-lg">
                                                👥
                                            </div>
                                            <h4 class="font-bold text-slate-800 text-sm">Data Pegawai</h4>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-relaxed mb-5">Daftar master data pegawai pemerintah kota, unit kerja, dan jabatan.</p>
                                    </div>
                                    <a href="{{ route('admin.rekap.cetak', 'master_pegawai') }}" target="_blank" class="w-full text-center inline-flex justify-center items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                        🖨️ Cetak Rekap Pegawai
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION: DATA PENGADUAN -->
                        <div>
                            <div class="flex items-center gap-4 mb-5">
                                <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Cetak Data Pengaduan</h4>
                                <div class="flex-1 h-px bg-slate-300"></div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Card: Ekspor Excel -->
                                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="p-2.5 bg-green-50 text-green-600 rounded-lg text-lg">
                                                🟩
                                            </div>
                                            <h4 class="font-bold text-slate-800 text-sm">Ekspor Data (Excel)</h4>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-relaxed mb-5">Unduh data mentah seluruh pengaduan ke dalam format Microsoft Excel (.xlsx) untuk diolah lebih lanjut.</p>
                                    </div>
                                    <a href="{{ route('admin.kasus.export.excel') }}" class="w-full text-center inline-flex justify-center items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                        📊 Unduh Excel (.xlsx)
                                    </a>
                                </div>

                                <!-- Card: Rekap Kasus -->
                                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="p-2.5 bg-blue-50 text-blue-600 rounded-lg text-lg">
                                                📁
                                            </div>
                                            <h4 class="font-bold text-slate-800 text-sm">Rekapitulasi Kasus</h4>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-relaxed mb-5">Rekap seluruh berkas pengaduan pelanggaran pegawai yang masuk ke dalam sistem.</p>
                                    </div>
                                    <a href="{{ route('admin.rekap.cetak', 'kasus') }}" target="_blank" class="w-full text-center inline-flex justify-center items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                        🖨️ Cetak Rekap Kasus
                                    </a>
                                </div>

                                <!-- Card: Info Tambahan -->
                                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="p-2.5 bg-teal-50 text-teal-600 rounded-lg text-lg">
                                                💬
                                            </div>
                                            <h4 class="font-bold text-slate-800 text-sm">Informasi Susulan Pelapor</h4>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-relaxed mb-5">Rekapitulasi pesan tambahan dan berkas susulan yang dikirimkan oleh pelapor.</p>
                                    </div>
                                    <a href="{{ route('admin.rekap.cetak', 'tanggapan') }}" target="_blank" class="w-full text-center inline-flex justify-center items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                        🖨️ Cetak Rekap Info
                                    </a>
                                </div>

                                <!-- Card: Hasil Investigasi -->
                                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="p-2.5 bg-cyan-50 text-cyan-600 rounded-lg text-lg">
                                                🔍
                                            </div>
                                            <h4 class="font-bold text-slate-800 text-sm">Hasil Investigasi</h4>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-relaxed mb-5">Kertas kerja, fakta temuan lapangan, dan kesimpulan tim audit investigasi.</p>
                                    </div>
                                    <a href="{{ route('admin.rekap.cetak', 'investigasi') }}" target="_blank" class="w-full text-center inline-flex justify-center items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                        🖨️ Cetak Rekap Audit
                                    </a>
                                </div>

                                <!-- Card: Tindak Lanjut -->
                                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-lg text-lg">
                                                ⚖️
                                            </div>
                                            <h4 class="font-bold text-slate-800 text-sm">Tindak Lanjut & Sanksi</h4>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-relaxed mb-5">Arsip keputusan sanksi final, instansi penindak, dan tanggal eksekusi keputusan.</p>
                                    </div>
                                    <a href="{{ route('admin.rekap.cetak', 'tindaklanjut') }}" target="_blank" class="w-full text-center inline-flex justify-center items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                        🖨️ Cetak Rekap Sanksi
                                    </a>
                                </div>

                                <!-- Card: Arsip Bukti -->
                                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="p-2.5 bg-purple-50 text-purple-600 rounded-lg text-lg">
                                                📎
                                            </div>
                                            <h4 class="font-bold text-slate-800 text-sm">Arsip Berkas Bukti</h4>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-relaxed mb-5">Daftar berkas bukti awal pelapor, berkas tambahan, dan berkas audit lapangan.</p>
                                    </div>
                                    <a href="{{ route('admin.rekap.cetak', 'bukti') }}" target="_blank" class="w-full text-center inline-flex justify-center items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                        🖨️ Cetak Rekap Bukti
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <!-- MESIN PENCARI LIVE (GLOBAL SEARCH) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('kolomPencarian');
            
            searchInput.addEventListener('keyup', function(e) {
                const keyword = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    if (row.querySelector('td[colspan]')) return;
                    const rowText = row.textContent.toLowerCase();
                    
                    if (rowText.includes(keyword)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>