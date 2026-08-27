import re

admin_path = r'c:\laragon\www\wbs-pemko-bjm\resources\views\admin\dashboard.blade.php'

with open(admin_path, 'r', encoding='utf-8') as f:
    c = f.read()

# 1. Compact Sidebar Top Header
c = c.replace(
    '''        <div class="h-16 flex items-center gap-3 px-5 border-b border-slate-700/50 bg-slate-900/50">
            <img src="{{ asset('images/logo-bjm.png') }}" alt="Pemko Banjarmasin" class="w-10 h-auto">
            <div class="leading-tight">
                <span class="text-white font-bold text-[15px] tracking-wide block">Admin Pengawasan</span>
                <span class="text-bjm-gold text-[10px] uppercase font-bold tracking-widest block">Kota Banjarmasin</span>
            </div>
        </div>''',
    '''        <div class="h-14 flex items-center gap-2.5 px-4 border-b border-slate-700/50 bg-slate-900/50">
            <img src="{{ asset('images/logo-bjm.png') }}" alt="Pemko Banjarmasin" class="w-8 h-auto">
            <div class="leading-tight">
                <span class="text-white font-bold text-sm tracking-wide block">Admin Pengawasan</span>
                <span class="text-bjm-gold text-[9px] uppercase font-bold tracking-widest block">Kota Banjarmasin</span>
            </div>
        </div>'''
)

# 2. Compact Sidebar Buttons & Headings
c = c.replace('px-3 py-2.5 rounded-r-lg text-sm font-medium', 'px-3 py-2 rounded-r-lg text-xs font-medium')
c = c.replace('<svg class="w-5 h-5 opacity-75"', '<svg class="w-4 h-4 opacity-75"')
c = c.replace('uppercase tracking-wider mb-2 mt-6', 'uppercase tracking-wider mb-1.5 mt-4')
c = c.replace('uppercase tracking-wider mb-2 mt-4', 'uppercase tracking-wider mb-1.5 mt-3')

# 3. Compact Top Header Bar & Remove Top Search Input
c = c.replace(
    '''        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10">
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
        </header>''',
    '''        <header class="h-14 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-700 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="hidden sm:flex items-center gap-2 text-xs font-semibold text-slate-600 bg-slate-100/80 border border-slate-200 px-3 py-1.5 rounded-lg shadow-inner">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Portal Administrator • Kota Banjarmasin</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block leading-tight">
                    <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] font-semibold text-amber-600">Administrator Utama</p>
                </div>
                <div class="w-8 h-8 rounded-full bg-slate-900 text-amber-400 border border-amber-500/40 flex items-center justify-center font-bold text-xs shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="bg-slate-100 hover:bg-red-50 text-slate-500 hover:text-red-600 p-1.5 rounded-lg transition-all" title="Keluar dari Admin">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </header>'''
)

# 4. Compact Hero Banner
c = c.replace(
    '''        <div class="bg-bjm-dark pt-10 pb-24 px-4 sm:px-6 lg:px-8 border-b-4 border-bjm-gold">
            <div class="flex justify-between items-center">
                <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-white leading-tight">Manajemen Pelanggaran Dan Pelaporan Pegawai</h1>
            </div>
        </div>''',
    '''        <div class="bg-gradient-to-r from-slate-950 via-bjm-dark to-slate-900 pt-5 pb-14 px-4 sm:px-6 lg:px-8 border-b-2 border-bjm-gold relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-6 -mr-6 w-48 h-48 bg-bjm-gold rounded-full blur-3xl opacity-10 pointer-events-none"></div>
            <div class="relative z-10">
                <h1 class="text-lg sm:text-xl font-bold text-white tracking-tight leading-snug">Manajemen Pelanggaran Dan Pelaporan Pegawai</h1>
                <p class="text-slate-400 text-xs mt-0.5">Pusat kendali laporan pengaduan, pengawasan kinerja, dan tindak lanjut kasus ASN Kota Banjarmasin.</p>
            </div>
        </div>'''
)

# 5. Compact KPI Cards
old_kpi = '''        <div class="-mt-16 px-4 sm:px-6 lg:px-8 pb-8">
            
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
            </div>'''

new_kpi = '''        <div class="-mt-8 px-4 sm:px-6 lg:px-8 pb-5">
            
            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border-l-4 border-emerald-500 px-4 py-2.5 rounded-r-lg shadow-sm flex items-center gap-2.5 font-bold text-emerald-800 text-xs">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 px-4 py-2.5 rounded-r-lg shadow-sm flex items-center gap-2.5 font-bold text-red-800 text-xs">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-3 rounded-r-lg shadow-sm flex items-start gap-2.5">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="text-xs text-red-700 font-bold mb-0.5">Ada kesalahan input:</p>
                        <ul class="list-disc list-inside text-[11px] text-red-600 space-y-0.5">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-3.5">
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-slate-200 p-3 sm:p-3.5 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-1.5">
                        <div>
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Total Kasus</p>
                            <h3 class="text-xl sm:text-2xl font-black text-slate-800">{{ $dataKasus->count() }}</h3>
                        </div>
                        <div class="p-2 bg-slate-100 text-slate-600 rounded-lg shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400">Semua laporan terdaftar.</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-slate-200 p-3 sm:p-3.5 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-1.5">
                        <div>
                            <p class="text-[11px] font-bold text-amber-600 uppercase tracking-wider mb-0.5">Verifikasi</p>
                            <h3 class="text-xl sm:text-2xl font-black text-slate-800">{{ $dataKasus->where('status', 'masuk')->count() }}</h3>
                        </div>
                        <div class="p-2 bg-amber-50 text-amber-600 rounded-lg shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400">Menunggu verifikasi.</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-slate-200 p-3 sm:p-3.5 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-1.5">
                        <div>
                            <p class="text-[11px] font-bold text-blue-600 uppercase tracking-wider mb-0.5">Audit Lapangan</p>
                            <h3 class="text-xl sm:text-2xl font-black text-slate-800">{{ $dataKasus->where('status', 'investigasi')->count() }}</h3>
                        </div>
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400">Proses investigasi.</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-slate-200 p-3 sm:p-3.5 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-1.5">
                        <div>
                            <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider mb-0.5">Selesai</p>
                            <h3 class="text-xl sm:text-2xl font-black text-slate-800">{{ $dataKasus->where('status', 'selesai')->count() }}</h3>
                        </div>
                        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400">Penyidikan ditutup.</p>
                </div>
            </div>'''

if old_kpi in c:
    c = c.replace(old_kpi, new_kpi)

# 6. Compact Content Margin & Beranda Tab
c = c.replace(
    '''            <div class="mt-8 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                
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
                </div>''',
    '''            <div class="mt-4 sm:mt-5 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                
                <div x-show="tab === 'beranda'" x-transition.opacity>
                    <div class="px-5 py-5 border-b border-slate-200 bg-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-48 h-48 bg-bjm-gold/5 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none"></div>
                        <div class="relative z-10">
                            <h2 class="text-base sm:text-lg font-bold text-slate-800 mb-1">Selamat Datang di Meja Kerja Admin Pengawasan 👋</h2>
                            <p class="text-slate-500 text-xs mb-4 max-w-2xl leading-relaxed">Ini adalah pusat kendali Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai Pemerintah Kota Banjarmasin. Pantau pengaduan, investigasi, dan seluruh arsip penindakan ASN dari satu portal terpadu.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 mt-3">
                                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 shadow-sm">
                                    <div class="flex items-center gap-2.5 mb-2.5">
                                        <div class="bg-amber-100 p-2 rounded-lg text-amber-700 border border-amber-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-800">Komitmen Pemko Banjarmasin</h3>
                                    </div>
                                    <p class="text-slate-600 text-xs leading-relaxed italic">
                                        "Sejalan dengan semangat <strong>Banjarmasin Baiman (Barasih wan Nyaman)</strong>, Pemerintah Kota berkomitmen menghadirkan birokrasi yang bersih, profesional, dan berintegritas dari praktik pelanggaran."
                                    </p>
                                </div>

                                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 shadow-sm">
                                    <div class="flex items-center gap-2.5 mb-2.5">
                                        <div class="bg-blue-100 p-2 rounded-lg text-blue-700 border border-blue-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-800">Standar Pengawasan Internal</h3>
                                    </div>
                                    <ul class="space-y-1.5 text-slate-600 text-xs">
                                        <li class="flex items-start gap-2">
                                            <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span><strong>Anonimitas Terproteksi:</strong> Identitas pelapor dilindungi kerahasiaannya.</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span><strong>Independensi Audit:</strong> Telaah objektif oleh tim Verifikator & Investigator.</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span><strong>Transparansi Putusan:</strong> Progres penindakan dapat dipantau berkala.</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>'''
)

# 7. Compact Tab Headers across all data tabs
c = c.replace('px-6 py-5 border-b border-slate-200', 'px-5 py-3.5 border-b border-slate-200')
c = c.replace('text-lg font-bold text-slate-800', 'text-base font-bold text-slate-800')

with open(admin_path, 'w', encoding='utf-8') as f:
    f.write(c)

print("Admin dashboard compacted and top search removed successfully.")
