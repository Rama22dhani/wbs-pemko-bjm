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


        // Helper DataTables Client-Side Manager
        function tableManager(tableId, totalRows) {
            return {
                tableId: tableId,
                search: '',
                perPage: 10,
                currentPage: 1,
                totalItems: totalRows,
                filteredCount: totalRows,
                get totalPages() { return Math.ceil(this.filteredCount / this.perPage) || 1; },
                get startIndex() {
                    if (this.filteredCount === 0) return 0;
                    return (this.currentPage - 1) * this.perPage + 1;
                },
                get endIndex() { return Math.min(this.currentPage * this.perPage, this.filteredCount); },
                initTable() { this.updateTable(); },
                updateTable() {
                    const table = document.getElementById(this.tableId);
                    if (!table) return;
                    const rows = Array.from(table.querySelectorAll('tbody tr[data-row="true"]'));
                    const s = this.search.toLowerCase().trim();

                    let matchedRows = [];
                    rows.forEach(row => {
                        const text = row.innerText.toLowerCase();
                        if (!s || text.includes(s)) { matchedRows.push(row); } else { row.style.display = 'none'; }
                    });

                    this.filteredCount = matchedRows.length;
                    if (this.currentPage > this.totalPages) { this.currentPage = this.totalPages || 1; }
                    if (this.currentPage < 1) this.currentPage = 1;

                    const start = (this.currentPage - 1) * this.perPage;
                    const end = start + parseInt(this.perPage);

                    matchedRows.forEach((row, idx) => {
                        if (idx >= start && idx < end) {
                            row.style.display = '';
                            const noCell = row.querySelector('.col-no');
                            if (noCell) noCell.textContent = (idx + 1);
                        } else { row.style.display = 'none'; }
                    });
                },
                nextPage() { if (this.currentPage < this.totalPages) { this.currentPage++; this.updateTable(); } },
                prevPage() { if (this.currentPage > 1) { this.currentPage--; this.updateTable(); } },
                goToPage(p) { this.currentPage = p; this.updateTable(); }
            };
        }

        // Helper Sort Column on Header Click
        function sortTable(tableId, colIndex) {
            const table = document.getElementById(tableId);
            if (!table) return;
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr[data-row="true"]'));
            const currentDir = table.getAttribute('data-sort-dir') === 'asc' ? 'desc' : 'asc';
            table.setAttribute('data-sort-dir', currentDir);

            rows.sort((a, b) => {
                const cellA = a.children[colIndex] ? a.children[colIndex].innerText.trim() : '';
                const cellB = b.children[colIndex] ? b.children[colIndex].innerText.trim() : '';
                return currentDir === 'asc' 
                    ? cellA.localeCompare(cellB, undefined, {numeric: true}) 
                    : cellB.localeCompare(cellA, undefined, {numeric: true});
            });

            rows.forEach(r => tbody.appendChild(r));
            const container = table.closest('[x-data]');
            if (container && container._x_dataStack && container._x_dataStack[0]) { container._x_dataStack[0].updateTable(); }
        }

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
        
        <div class="h-14 flex items-center gap-2.5 px-4 border-b border-slate-700/50 bg-slate-900/50">
            <img src="{{ asset('images/logo-bjm.png') }}" alt="Pemko Banjarmasin" class="w-8 h-auto">
            <div class="leading-tight">
                <span class="text-white font-bold text-sm tracking-wide block">Admin Pengawasan</span>
                <span class="text-bjm-gold text-[9px] uppercase font-bold tracking-widest block">Kota Banjarmasin</span>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto py-5 px-3 space-y-1">
            
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
            <button @click="tab = 'kategori'; sidebarOpen = false" :class="tab === 'kategori' ? 'bg-bjm-gold/10 text-bjm-gold border-l-4 border-bjm-gold' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm font-medium transition-colors">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Data Kategori
            </button>
            <button @click="tab = 'kasus'; sidebarOpen = false" :class="tab === 'kasus' ? 'bg-bjm-gold/10 text-bjm-gold border-l-4 border-bjm-gold' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm font-medium transition-colors">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Data Kasus
            </button>
            <button @click="tab = 'investigasi'; sidebarOpen = false" :class="tab === 'investigasi' ? 'bg-bjm-gold/10 text-bjm-gold border-l-4 border-bjm-gold' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm font-medium transition-colors">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Data Investigasi
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
        
        <header class="h-14 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10 border-b border-slate-200">
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
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </header>

        <div class="bg-gradient-to-r from-slate-950 via-bjm-dark to-slate-900 pt-8 pb-20 px-4 sm:px-6 lg:px-8 border-b-4 border-bjm-gold relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-6 -mr-6 w-48 h-48 bg-bjm-gold rounded-full blur-3xl opacity-10 pointer-events-none"></div>
            <div class="relative z-10">
                <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight leading-snug">Manajemen Pelanggaran Dan Pelaporan Pegawai</h1>
                <p class="text-slate-400 text-sm mt-1">Pusat kendali laporan pengaduan, pengawasan kinerja, dan tindak lanjut kasus ASN Kota Banjarmasin.</p>
            </div>
        </div>

        <div class="-mt-12 px-4 sm:px-6 lg:px-8 pb-8 relative z-20">
            
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
                        <p class="text-xs text-red-700 font-bold mb-1">Ada kesalahan input:</p>
                        <ul class="list-disc list-inside text-[11px] text-red-600 space-y-0.5">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-slate-200 p-5 sm:p-6 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Kasus</p>
                            <h3 class="text-2xl sm:text-3xl font-black text-slate-800">{{ $dataKasus->count() }}</h3>
                        </div>
                        <div class="p-3 bg-slate-100 text-slate-600 rounded-lg shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Semua laporan terdaftar.</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-slate-200 p-5 sm:p-6 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-xs font-bold text-amber-600 uppercase tracking-wider mb-1">Verifikasi</p>
                            <h3 class="text-2xl sm:text-3xl font-black text-slate-800">{{ $dataKasus->where('status', 'masuk')->count() }}</h3>
                        </div>
                        <div class="p-3 bg-amber-50 text-amber-600 rounded-lg shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Menunggu verifikasi.</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-slate-200 p-5 sm:p-6 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Audit Lapangan</p>
                            <h3 class="text-2xl sm:text-3xl font-black text-slate-800">{{ $dataKasus->where('status', 'investigasi')->count() }}</h3>
                        </div>
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-lg shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Proses investigasi.</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-slate-200 p-5 sm:p-6 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Selesai</p>
                            <h3 class="text-2xl sm:text-3xl font-black text-slate-800">{{ $dataKasus->where('status', 'selesai')->count() }}</h3>
                        </div>
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Penyidikan ditutup.</p>
                </div>
            </div>

            <div class="mt-6 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                
                <div x-show="tab === 'beranda'" x-transition.opacity>
                    <div class="px-6 py-6 border-b border-slate-200 bg-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-48 h-48 bg-bjm-gold/5 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none"></div>
                        <div class="relative z-10">
                            <h2 class="text-base sm:text-lg font-bold text-slate-800 mb-1">Selamat Datang di Meja Kerja Admin Pengawasan 👋</h2>
                            <p class="text-slate-600 text-sm mb-6 max-w-2xl leading-relaxed">Ini adalah pusat kendali Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai Pemerintah Kota Banjarmasin. Pantau pengaduan, investigasi, dan seluruh arsip penindakan ASN dari satu portal terpadu.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 shadow-sm">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="bg-amber-100 p-2.5 rounded-xl text-amber-700 border border-amber-200">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-800">Komitmen Pemko Banjarmasin</h3>
                                    </div>
                                    <p class="text-slate-600 text-sm leading-relaxed italic">
                                        "Sejalan dengan semangat <strong>Banjarmasin Baiman (Barasih wan Nyaman)</strong>, Pemerintah Kota berkomitmen menghadirkan birokrasi yang bersih, profesional, dan berintegritas dari praktik pelanggaran."
                                    </p>
                                </div>

                                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 shadow-sm">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="bg-blue-100 p-2.5 rounded-xl text-blue-700 border border-blue-200">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-800">Standar Pengawasan Internal</h3>
                                    </div>
                                    <ul class="space-y-3 text-slate-600 text-sm">
                                        <li class="flex items-start gap-2">
                                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span><strong>Anonimitas Terproteksi:</strong> Identitas pelapor dilindungi kerahasiaannya.</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span><strong>Independensi Audit:</strong> Telaah objektif oleh tim Verifikator & Investigator.</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span><strong>Transparansi Putusan:</strong> Progres penindakan dapat dipantau berkala.</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MENU 0: MASTER DATA PEGAWAI (BARU) -->
                <div x-show="tab === 'master_pegawai'" x-transition.opacity style="display: none;" 
                    x-init="initTable()" x-data="{ ...tableManager('table-master-pegawai', {{ count($dataMasterPegawai) }}), showModalMaster: false, editModeMaster: false, formMaster: { id: '', user_id: '', nip: '', nama_pegawai: '', jenis_kelamin: 'Laki-laki', tempat_lahir: '', tanggal_lahir: '', alamat: '', status_kepegawaian: 'PNS', asal_instansi: '', jabatan: '', nomor_hp: '', status_aktif: 'Aktif' } }">
                    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Master Data Pegawai</h3>
                            <span class="bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold px-3 py-1 rounded-full mt-2 inline-block">Total: {{ count($dataMasterPegawai) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.rekap.cetak', 'master_pegawai') }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all transform hover:scale-105">
                                🖨️ Cetak Pegawai
                            </a>
                            <button @click="showModalMaster = true; editModeMaster = false; formMaster = { id: '', user_id: '', nip: '', nama_pegawai: '', jenis_kelamin: 'Laki-laki', tempat_lahir: '', tanggal_lahir: '', alamat: '', status_kepegawaian: 'PNS', asal_instansi: '', jabatan: '', nomor_hp: '', status_aktif: 'Aktif' }" class="bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 text-white text-xs font-bold px-3.5 py-2 rounded-lg flex items-center gap-1.5 transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Data Pegawai
                            </button>
                        </div>
                    </div>

    <div class="px-4 py-2.5 bg-white border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="flex items-center gap-1.5 font-medium">
            <span>Tampilkan</span>
            <select x-model="perPage" @change="updateTable()" class="bg-white border border-slate-300 rounded px-2.5 py-1 text-xs font-semibold focus:border-bjm-gold outline-none">
                <option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50">50</option>
            </select>
            <span>data</span>
        </div>
        <div class="flex items-center gap-2">
            <label class="font-semibold text-slate-600">Cari Data:</label>
            <input type="text" x-model="search" @input="updateTable()" placeholder="Ketik untuk mencari..." class="bg-white border border-slate-300 rounded px-3 py-1.5 text-xs focus:border-bjm-gold outline-none w-52 sm:w-64">
        </div>
    </div>
                    <div class="overflow-x-auto">
                        <table id="table-master-pegawai" class="w-full text-left border-collapse border border-slate-200">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-master-pegawai', 0)">NIP <span class="text-slate-400">⇅</span></th>
                                    <th class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-master-pegawai', 1)">Nama <span class="text-slate-400">⇅</span></th>
                                    <th class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-master-pegawai', 2)">Jenis Kelamin <span class="text-slate-400">⇅</span></th>
                                    <th class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-master-pegawai', 3)">Status <span class="text-slate-400">⇅</span></th>
                                    <th class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-master-pegawai', 4)">Instansi <span class="text-slate-400">⇅</span></th>
                                    <th class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-master-pegawai', 5)">Jabatan <span class="text-slate-400">⇅</span></th>
                                    <th class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 text-center">Akun Terhubung</th>
                                    <th class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 text-center pr-6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @foreach($dataMasterPegawai as $mp)
                                <tr data-row="true" class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition">
                                    <td class="px-3.5 py-2.5 font-mono text-slate-600">{{ $mp->nip }}</td>
                                    <td class="px-3.5 py-2.5 font-bold text-slate-800">{{ $mp->nama_pegawai }}</td>
                                    <td class="px-3.5 py-2.5 text-slate-700">{{ $mp->jenis_kelamin ?? '-' }}</td>
                                    <td class="px-3.5 py-2.5">
                                        <span class="px-2.5 py-1 rounded text-[10px] uppercase font-bold border {{ $mp->status_kepegawaian == 'PNS' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}">{{ $mp->status_kepegawaian }}</span>
                                    </td>
                                    <td class="px-3.5 py-2.5 text-xs text-slate-600">{{ $mp->asal_instansi }}</td>
                                    <td class="px-3.5 py-2.5 text-slate-700">{{ $mp->jabatan }}</td>
                                    <td class="px-3.5 py-2.5 text-center">
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
                                    <td class="px-3.5 py-2.5 text-center pr-6">
                                        <div class="flex items-center justify-center gap-2">
                                            <button @click="showModalMaster = true; editModeMaster = true; formMaster = { 
                                                id: '{{ $mp->id }}', user_id: '{{ $mp->user_id }}', nip: '{{ $mp->nip }}', nama_pegawai: '{{ addslashes($mp->nama_pegawai) }}', jenis_kelamin: '{{ $mp->jenis_kelamin }}', tempat_lahir: '{{ addslashes($mp->tempat_lahir) }}', tanggal_lahir: '{{ $mp->tanggal_lahir }}', alamat: '{{ addslashes(preg_replace('/\r|\n/', ' ', $mp->alamat)) }}', status_kepegawaian: '{{ $mp->status_kepegawaian }}', asal_instansi: '{{ addslashes($mp->asal_instansi) }}', jabatan: '{{ addslashes($mp->jabatan) }}', nomor_hp: '{{ $mp->nomor_hp }}', status_aktif: '{{ $mp->status_aktif }}' 
                                            }" class="p-1.5 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-md transition-all shadow-xs" title="Edit Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('admin.master_pegawai.destroy', $mp->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus profil data pegawai ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-md transition-all shadow-xs" title="Hapus Data">
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
    <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="font-medium">
            Menampilkan <span class="font-bold text-slate-800" x-text="startIndex"></span> sampai <span class="font-bold text-slate-800" x-text="endIndex"></span> dari <span class="font-bold text-slate-800" x-text="filteredCount"></span> total data
        </div>
        <div class="flex items-center gap-1">
            <button type="button" @click="prevPage()" :disabled="currentPage <= 1" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Sebelumnya</button>
            <template x-for="p in totalPages" :key="p">
                <button type="button" @click="goToPage(p)" :class="currentPage === p ? 'bg-amber-500 text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-100'" class="px-3 py-1 border border-slate-300 rounded text-xs transition" x-text="p"></button>
            </template>
            <button type="button" @click="nextPage()" :disabled="currentPage >= totalPages" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Selanjutnya</button>
        </div>
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
                                        <label class="block text-lg font-bold text-slate-700 mb-1">NIP Pegawai <span class="text-red-500">*</span></label>
                                        <input type="text" name="nip" x-model="formMaster.nip" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                        <input type="text" name="nama_pegawai" x-model="formMaster.nama_pegawai" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" x-model="formMaster.jenis_kelamin" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" x-model="formMaster.tempat_lahir" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" x-model="formMaster.tanggal_lahir" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                                        <textarea name="alamat" x-model="formMaster.alamat" rows="2" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Status Kepegawaian <span class="text-red-500">*</span></label>
                                        <select name="status_kepegawaian" x-model="formMaster.status_kepegawaian" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                            <option value="PNS">PNS</option>
                                            <option value="PPPK">PPPK</option>
                                            <option value="CPNS">CPNS</option>
                                            <option value="Honorer">Honorer</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Asal Instansi <span class="text-red-500">*</span></label>
                                        <input type="text" name="asal_instansi" x-model="formMaster.asal_instansi" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Jabatan <span class="text-red-500">*</span></label>
                                        <input type="text" name="jabatan" x-model="formMaster.jabatan" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Nomor HP</label>
                                        <input type="text" name="nomor_hp" x-model="formMaster.nomor_hp" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Akun Login Terhubung (Opsional)</label>
                                        <select name="user_id" x-model="formMaster.user_id" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                            <option value="">-- Tidak Terhubung / Tanpa Akun --</option>
                                            @foreach($dataPegawai as $akun)
                                                <option value="{{ $akun->id }}">{{ $akun->name }} ({{ $akun->peran }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Status Aktif <span class="text-red-500">*</span></label>
                                        <select name="status_aktif" x-model="formMaster.status_aktif" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                            <option value="Aktif">Aktif</option>
                                            <option value="Nonaktif">Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100">
                                    <button type="button" @click="showModalMaster = false" class="px-5 py-2.5 text-lg font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 text-lg font-bold text-white bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 rounded-lg transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">Simpan Profil Pegawai</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- MENU 0.5: MASTER DATA KATEGORI -->
                <div x-show="tab === 'kategori'" x-transition.opacity style="display: none;"
                    x-init="initTable()" x-data="{ ...tableManager('table-kategori', {{ count($kategoris) }}), showModal: false, editMode: false, form: { id: '', nama_kategori: '', deskripsi: '' } }">
                    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Master Data Kategori</h3>
                            <span class="bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold px-3 py-1 rounded-full mt-2 inline-block">Total: {{ count($kategoris) }}</span>
                        </div>
                        <div>
                            <button @click="showModal = true; editMode = false; form = { id: '', nama_kategori: '', deskripsi: '' }" class="bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 text-white text-xs font-bold px-4 py-2 rounded-lg flex items-center gap-2 transition-all shadow-md shadow-amber-500/20 transform hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Kategori
                            </button>
                        </div>
                    </div>

                    <div class="px-4 py-2.5 bg-white border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
                        <div class="flex items-center gap-1.5 font-medium">
                            <span>Tampilkan</span>
                            <select x-model="perPage" @change="updateTable()" class="bg-white border border-slate-300 rounded px-2.5 py-1 text-xs font-semibold focus:border-bjm-gold outline-none">
                                <option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50">50</option>
                            </select>
                            <span>data</span>
                        </div>
                        <div class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" x-model="search" @input="updateTable()" placeholder="Cari kategori..." class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-bjm-gold/30 focus:border-bjm-gold outline-none transition-all placeholder:text-slate-400">
                        </div>
                    </div>

                    <div class="overflow-x-auto bg-white">
                        <table id="table-kategori" class="w-full text-left text-sm whitespace-nowrap">
                            <thead>
                                <tr class="bg-slate-100/50 text-slate-500 text-[10px] uppercase tracking-wider font-bold border-b border-slate-200">
                                    <th class="px-4 py-3 text-center w-12">No</th>
                                    <th class="px-4 py-3">Nama Kategori</th>
                                    <th class="px-4 py-3">Deskripsi</th>
                                    <th class="px-4 py-3 text-center">Jumlah Laporan</th>
                                    <th class="px-4 py-3 text-center w-28">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($kategoris as $index => $item)
                                    <tr data-row="true" class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-4 py-3 text-center text-slate-500 font-medium text-xs col-no">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 font-bold text-slate-800">{{ $item->nama_kategori }}</td>
                                        <td class="px-4 py-3 text-slate-600 truncate max-w-xs" title="{{ $item->deskripsi }}">{{ Str::limit($item->deskripsi, 50) ?: '-' }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs font-bold">{{ $item->pengaduans_count }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button @click="showModal = true; editMode = true; form = { id: '{{ $item->id }}', nama_kategori: '{{ addslashes($item->nama_kategori) }}', deskripsi: '{{ addslashes($item->deskripsi) }}' }" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                                @if($item->pengaduans_count == 0)
                                                <form action="{{ route('kategori.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                                @else
                                                <button type="button" class="p-1.5 text-slate-300 cursor-not-allowed" title="Tidak dapat dihapus karena digunakan pada pengaduan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr data-row="false">
                                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                            <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            <p class="font-medium text-sm">Belum ada data kategori</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-3 border-t border-slate-200 bg-slate-50 flex items-center justify-between text-xs" x-show="filteredCount > 0">
                        <span class="text-slate-600 font-medium">Menampilkan <span class="font-bold text-slate-800" x-text="startIndex"></span> - <span class="font-bold text-slate-800" x-text="endIndex"></span> dari <span class="font-bold text-slate-800" x-text="filteredCount"></span></span>
                        <div class="flex items-center gap-1">
                            <button type="button" @click="prevPage()" :disabled="currentPage <= 1" class="px-3 py-1.5 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed font-medium transition-colors">Sebelumnya</button>
                            <template x-for="p in totalPages" :key="p">
                                <button type="button" @click="goToPage(p)" :class="currentPage === p ? 'bg-amber-500 text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-100'" class="px-3 py-1.5 border border-slate-300 rounded text-xs transition" x-text="p"></button>
                            </template>
                            <button type="button" @click="nextPage()" :disabled="currentPage >= totalPages" class="px-3 py-1.5 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed font-medium transition-colors">Selanjutnya</button>
                        </div>
                    </div>

                    <!-- Modal Form Kategori -->
                    <div x-show="showModal" class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden p-4" style="display: none;">
                        <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModal = false"></div>
                        <div x-show="showModal" x-transition.scale.origin.bottom class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-full overflow-hidden flex flex-col">
                            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                <h3 class="text-lg font-extrabold text-slate-800" x-text="editMode ? 'Edit Kategori' : 'Tambah Kategori Baru'"></h3>
                                <button @click="showModal = false" class="text-slate-400 hover:text-red-500 transition-colors p-1 rounded-lg hover:bg-red-50"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>
                            <form :action="editMode ? '{{ url('kategori') }}/' + form.id : '{{ route('kategori.store') }}'" method="POST" class="flex-1 overflow-y-auto">
                                @csrf
                                <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editMode">
                                <div class="p-6 space-y-5">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                                        <input type="text" name="nama_kategori" x-model="form.nama_kategori" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:border-bjm-gold focus:bg-white focus:ring-2 focus:ring-bjm-gold/30 outline-none transition-all" placeholder="Contoh: Gratifikasi">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Deskripsi (Opsional)</label>
                                        <textarea name="deskripsi" x-model="form.deskripsi" rows="3" class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:border-bjm-gold focus:bg-white focus:ring-2 focus:ring-bjm-gold/30 outline-none transition-all" placeholder="Tuliskan deskripsi kategori..."></textarea>
                                    </div>
                                </div>
                                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3 rounded-b-2xl">
                                    <button type="button" @click="showModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">Batal</button>
                                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-bjm-gold hover:bg-amber-600 rounded-xl shadow-md shadow-amber-500/20 transition-all transform hover:-translate-y-0.5" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Kategori'"></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- MENU 1: DATA AKSES (LOGIN) -->
                <div x-show="tab === 'pegawai'" x-transition.opacity style="display: none;" 
                    x-init="initTable()" x-data="{ ...tableManager('table-pegawai', {{ count($dataPegawai) }}),  showModal: false, editMode: false, form: { id: '', name: '', email: '', peran: 'investigator' } }">
                    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Data Akses Pengawas (Login)</h3>
                            <span class="bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold px-3 py-1 rounded-full mt-2 inline-block">Total: {{ count($dataPegawai) }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.rekap.cetak', 'pegawai') }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all transform hover:scale-105">
                                🖨️ Cetak Akses
                            </a>
                            <button @click="showModal = true; editMode = false; form = { id: '', name: '', email: '', peran: 'investigator' }" class="bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 text-white text-xs font-bold px-3.5 py-2 rounded-lg flex items-center gap-1.5 transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Buat Akun Akses
                            </button>
                        </div>
                    </div>
                    
    <div class="px-4 py-2.5 bg-white border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="flex items-center gap-1.5 font-medium">
            <span>Tampilkan</span>
            <select x-model="perPage" @change="updateTable()" class="bg-white border border-slate-300 rounded px-2.5 py-1 text-xs font-semibold focus:border-bjm-gold outline-none">
                <option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50">50</option>
            </select>
            <span>data</span>
        </div>
        <div class="flex items-center gap-2">
            <label class="font-semibold text-slate-600">Cari Data:</label>
            <input type="text" x-model="search" @input="updateTable()" placeholder="Ketik untuk mencari..." class="bg-white border border-slate-300 rounded px-3 py-1.5 text-xs focus:border-bjm-gold outline-none w-52 sm:w-64">
        </div>
    </div>
    <div class="overflow-x-auto">
                        <table id="table-pegawai" class="w-full text-left border-collapse border border-slate-200">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-pegawai', 0)">Nama Pengguna <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-pegawai', 1)">Email Login <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-pegawai', 2)">Peran Sistem <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-pegawai', 3)">Aksi Akses <span class="text-slate-400">⇅</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @foreach($dataPegawai as $p)
                                <tr data-row="true" class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition">
                                    <td  class="px-3.5 py-2.5 font-semibold text-slate-800 border border-slate-200">{{ $p->name }}</td>
                                    <td  class="px-3.5 py-2.5 text-slate-500 font-medium border border-slate-200">{{ $p->email }}</td>
                                    <td  class="px-3.5 py-2.5 border border-slate-200">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">{{ strtoupper(str_replace('_', ' ', $p->peran)) }}</span>
                                    </td>
                                    <td  class="px-3.5 py-2.5 text-center border border-slate-200">
                                        <div class="flex items-center justify-center gap-2">
                                            <button @click="showModal = true; editMode = true; form = { id: '{{ $p->id }}', name: '{{ addslashes($p->name) }}', email: '{{ $p->email }}', peran: '{{ $p->peran }}' }" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-md transition-all shadow-xs" title="Edit Akses">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('admin.pegawai.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus akun akses ini? Profil fisiknya (jika ada) tidak akan terhapus.');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-md transition-all shadow-xs" title="Hapus Akses">
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
    <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="font-medium">
            Menampilkan <span class="font-bold text-slate-800" x-text="startIndex"></span> sampai <span class="font-bold text-slate-800" x-text="endIndex"></span> dari <span class="font-bold text-slate-800" x-text="filteredCount"></span> total data
        </div>
        <div class="flex items-center gap-1">
            <button type="button" @click="prevPage()" :disabled="currentPage <= 1" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Sebelumnya</button>
            <template x-for="p in totalPages" :key="p">
                <button type="button" @click="goToPage(p)" :class="currentPage === p ? 'bg-amber-500 text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-100'" class="px-3 py-1 border border-slate-300 rounded text-xs transition" x-text="p"></button>
            </template>
            <button type="button" @click="nextPage()" :disabled="currentPage >= totalPages" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Selanjutnya</button>
        </div>
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
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Nama Pengguna (Display)</label>
                                        <input type="text" name="name" x-model="form.name" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Email Aktif (Untuk Login)</label>
                                        <input type="email" name="email" x-model="form.email" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Peran Akses Sistem</label>
                                        <select name="peran" x-model="form.peran" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                            <option value="investigator">Investigator (Tim Lapangan)</option>
                                            <option value="admin">Administrator</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Kata Sandi <span x-show="editMode" class="text-xs text-slate-400 font-normal">(Kosongkan jika tidak diganti)</span></label>
                                        <input type="password" name="password" :required="!editMode" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none" placeholder="Minimal 8 karakter">
                                    </div>
                                </div>
                                <div class="mt-8 flex justify-end gap-3">
                                    <button type="button" @click="showModal = false" class="px-5 py-2.5 text-lg font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 text-lg font-bold text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 rounded-lg transition-all transform hover:scale-105 shadow-md shadow-blue-500/20">Simpan Akun</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- MENU 2: PELAPOR -->
                <div x-show="tab === 'pengguna'" x-transition.opacity style="display: none;"
                    x-init="initTable()" x-data="{ ...tableManager('table-pengguna', {{ count($dataPengguna) }}),  showModal: false, editMode: false, form: { id: '', name: '', email: '' } }">
                    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Data Pelapor Terdaftar</h3>
                            <span class="bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold px-3 py-1 rounded-full mt-2 inline-block">Total: {{ count($dataPengguna) }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.rekap.cetak', 'pengguna') }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all transform hover:scale-105">
                                🖨️ Cetak PDF
                            </a>
                            <button @click="showModal = true; editMode = false; form = { id: '', name: '', email: '' }" class="bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 text-white text-xs font-bold px-3.5 py-2 rounded-lg flex items-center gap-1.5 transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Pelapor
                            </button>
                        </div>
                    </div>
                    
    <div class="px-4 py-2.5 bg-white border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="flex items-center gap-1.5 font-medium">
            <span>Tampilkan</span>
            <select x-model="perPage" @change="updateTable()" class="bg-white border border-slate-300 rounded px-2.5 py-1 text-xs font-semibold focus:border-bjm-gold outline-none">
                <option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50">50</option>
            </select>
            <span>data</span>
        </div>
        <div class="flex items-center gap-2">
            <label class="font-semibold text-slate-600">Cari Data:</label>
            <input type="text" x-model="search" @input="updateTable()" placeholder="Ketik untuk mencari..." class="bg-white border border-slate-300 rounded px-3 py-1.5 text-xs focus:border-bjm-gold outline-none w-52 sm:w-64">
        </div>
    </div>
    <div class="overflow-x-auto">
                        <table id="table-pengguna" class="w-full text-left border-collapse border border-slate-200">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-pengguna', 0)">Nama Lengkap <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-pengguna', 1)">Email <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-pengguna', 2)">Tgl Mendaftar <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-pengguna', 3)">Aksi <span class="text-slate-400">⇅</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @foreach($dataPengguna as $u)
                                <tr data-row="true" class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition">
                                    <td  class="px-3.5 py-2.5 font-semibold text-slate-800 border border-slate-200">{{ $u->name }}</td>
                                    <td  class="px-3.5 py-2.5 text-slate-500 border border-slate-200">{{ $u->email }}</td>
                                    <td  class="px-3.5 py-2.5 text-slate-500 border border-slate-200">{{ $u->created_at->format('d M Y') }}</td>
                                    <td  class="px-3.5 py-2.5 text-center border border-slate-200">
                                        <div class="flex items-center justify-center gap-2">
                                            <button @click="showModal = true; editMode = true; form = { id: '{{ $u->id }}', name: '{{ addslashes($u->name) }}', email: '{{ $u->email }}' }" class="p-1.5 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-md transition-all shadow-xs" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('admin.pengguna.destroy', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pelapor ini? Laporan miliknya mungkin akan terdampak.');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-md transition-all shadow-xs" title="Hapus">
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
    <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="font-medium">
            Menampilkan <span class="font-bold text-slate-800" x-text="startIndex"></span> sampai <span class="font-bold text-slate-800" x-text="endIndex"></span> dari <span class="font-bold text-slate-800" x-text="filteredCount"></span> total data
        </div>
        <div class="flex items-center gap-1">
            <button type="button" @click="prevPage()" :disabled="currentPage <= 1" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Sebelumnya</button>
            <template x-for="p in totalPages" :key="p">
                <button type="button" @click="goToPage(p)" :class="currentPage === p ? 'bg-amber-500 text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-100'" class="px-3 py-1 border border-slate-300 rounded text-xs transition" x-text="p"></button>
            </template>
            <button type="button" @click="nextPage()" :disabled="currentPage >= totalPages" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Selanjutnya</button>
        </div>
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
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Nama Lengkap</label>
                                        <input type="text" name="name" x-model="form.name" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Email Aktif</label>
                                        <input type="email" name="email" x-model="form.email" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Kata Sandi <span x-show="editMode" class="text-xs text-slate-400 font-normal">(Kosongkan jika tidak diganti)</span></label>
                                        <input type="password" name="password" :required="!editMode" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none" placeholder="Minimal 8 karakter">
                                    </div>
                                </div>
                                <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100">
                                    <button type="button" @click="showModal = false" class="px-5 py-2.5 text-lg font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 text-lg font-bold text-white bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 rounded-lg transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">Simpan Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- MENU 3: DATA KASUS & INFO TAMBAHAN -->
                <div x-show="tab === 'kasus'" x-transition.opacity style="display: none;"
                    x-init="initTable()" x-data="{ ...tableManager('table-kasus', {{ count($dataKasus) }}),  
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
                            kategori_id: '', 
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
                    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Semua Data Laporan Pengaduan</h3>
                        </div>
                        <a href="{{ route('admin.rekap.cetak', 'kasus') }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all transform hover:scale-105">
                            🖨️ Cetak Rekap
                        </a>
                    </div>
                    
    <div class="px-4 py-2.5 bg-white border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="flex items-center gap-1.5 font-medium">
            <span>Tampilkan</span>
            <select x-model="perPage" @change="updateTable()" class="bg-white border border-slate-300 rounded px-2.5 py-1 text-xs font-semibold focus:border-bjm-gold outline-none">
                <option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50">50</option>
            </select>
            <span>data</span>
        </div>
        <div class="flex items-center gap-2">
            <label class="font-semibold text-slate-600">Cari Data:</label>
            <input type="text" x-model="search" @input="updateTable()" placeholder="Ketik untuk mencari..." class="bg-white border border-slate-300 rounded px-3 py-1.5 text-xs focus:border-bjm-gold outline-none w-52 sm:w-64">
        </div>
    </div>
    <div class="overflow-x-auto">
                        <table id="table-kasus" class="w-full text-left border-collapse border border-slate-200">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-kasus', 0)">Kode Kasus <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-kasus', 1)">Pelapor <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-kasus', 2)">Judul Laporan <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-kasus', 3)">Tingkat <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-kasus', 4)">Status <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-kasus', 5)">Aksi <span class="text-slate-400">⇅</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @forelse($dataKasus as $k)
                                <tr data-row="true" class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition">
                                    <td  class="px-3.5 py-2.5 font-mono font-bold text-slate-700 border border-slate-200">
                                        {{ $k->kode_tiket }}
                                        @if($k->pesan_susulan)
                                            <span class="block mt-1 text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded border border-blue-200 font-bold w-max">
                                                Ada Info Baru
                                            </span>
                                        @endif
                                    </td>
                                    <td  class="px-3.5 py-2.5 text-slate-700 font-medium border border-slate-200">{{ $k->user->name ?? 'Anonim' }}</td>
                                    <td  class="px-3.5 py-2.5 text-slate-600 border border-slate-200">{{ Str::limit($k->judul_laporan, 30) }}</td>
                                    
                                    <td  class="px-3.5 py-2.5 border border-slate-200">
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

                                    <td  class="px-3.5 py-2.5 border border-slate-200">
                                        <span class="px-3 py-1 text-[11px] uppercase rounded-full font-bold border 
                                            {{ $k->status == 'selesai' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 
                                                ($k->status == 'tindak_lanjut' ? 'bg-cyan-50 text-cyan-700 border-cyan-200' :
                                                ($k->status == 'investigasi' ? 'bg-blue-50 text-blue-700 border-blue-200' : 
                                                ($k->status == 'ditolak' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-amber-50 text-amber-700 border-amber-200'))) }}">
                                            {{ $k->status == 'tindak_lanjut' ? 'menunggu tindak lanjut' : $k->status }}
                                        </span>
                                    </td>
                                    <td  class="px-3.5 py-2.5 text-center border border-slate-200">
                                        <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                            
                                            <!-- Tombol Verifikasi dipindah ke Verifikator -->

                                            <a href="{{ route('admin.show', $k->id) }}" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-md transition-all shadow-xs" title="Lihat Detail Berkas">
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
                                                kategori_id: {{ json_encode($k->kategori_id) }}, 
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
                                            }' class="p-1.5 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-md transition-all shadow-xs" title="Edit Kasus Manual">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('admin.kasus.destroy', $k->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kasus ini secara permanen?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-md transition-all shadow-xs" title="Hapus Permanen">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6"  class="p-8 text-center text-slate-500 italic border border-slate-200">Belum ada data kasus masuk.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
    <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="font-medium">
            Menampilkan <span class="font-bold text-slate-800" x-text="startIndex"></span> sampai <span class="font-bold text-slate-800" x-text="endIndex"></span> dari <span class="font-bold text-slate-800" x-text="filteredCount"></span> total data
        </div>
        <div class="flex items-center gap-1">
            <button type="button" @click="prevPage()" :disabled="currentPage <= 1" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Sebelumnya</button>
            <template x-for="p in totalPages" :key="p">
                <button type="button" @click="goToPage(p)" :class="currentPage === p ? 'bg-amber-500 text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-100'" class="px-3 py-1 border border-slate-300 rounded text-xs transition" x-text="p"></button>
            </template>
            <button type="button" @click="nextPage()" :disabled="currentPage >= totalPages" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Selanjutnya</button>
        </div>
    </div>
    

                    <!-- Modal Verifikasi Kasus dihapus, pindah ke Verifikator -->

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
                                    <h4 class="text-lg font-bold text-bjm-dark border-b border-slate-200 pb-3 mb-4 flex items-center gap-2">
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
                                    <h4 class="text-lg font-bold text-bjm-dark border-b border-slate-200 pb-3 mb-4 flex items-center gap-2">
                                        <span>📝</span> Detail Laporan Utama
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-lg font-bold text-slate-700 mb-1">Judul Laporan <span class="text-red-500">*</span></label>
                                            <input type="text" name="judul_laporan" x-model="formKasus.judul_laporan" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-lg font-bold text-slate-700 mb-1">Kategori Pelanggaran <span class="text-red-500">*</span></label>
                                            <select name="kategori_id" x-model="formKasus.kategori_id" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                                <option value="">-- Pilih Klasifikasi Terdekat --</option>
                                                @foreach($kategoris as $kategori)
                                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-lg font-bold text-slate-700 mb-1">Tanggal Kejadian <span class="text-red-500">*</span></label>
                                            <input type="date" name="tanggal_kejadian" x-model="formKasus.tanggal_kejadian" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-lg font-bold text-slate-700 mb-1">Lokasi Kejadian <span class="text-red-500">*</span></label>
                                            <input type="text" name="lokasi_kejadian" x-model="formKasus.lokasi_kejadian" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Kronologi / Isi Laporan <span class="text-red-500">*</span></label>
                                        <textarea name="isi_laporan" x-model="formKasus.isi_laporan" rows="6" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Lampiran Bukti Awal (Gambar/PDF)</label>
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
                                    <h4 class="text-lg font-bold text-bjm-dark border-b border-slate-200 pb-3 mb-4 flex items-center gap-2">
                                        <span>🛡️</span> Tahap Verifikasi & Disposisi
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Status Kasus <span class="text-red-500">*</span></label>
                                            <select name="status" x-model="formKasus.status" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                                <option value="masuk">Masuk / Menunggu Verifikasi</option>
                                                <option value="investigasi">Proses Audit / Investigasi</option>
                                                <option value="tindak_lanjut">Menunggu Tindak Lanjut</option>
                                                <option value="selesai">Selesai 100% (Kasus Ditutup)</option>
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
                                    <h4 class="text-lg font-bold text-bjm-dark border-b border-slate-200 pb-3 mb-4 flex items-center gap-2">
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
                                    <button type="button" @click="showModalEditKasus = false" class="px-5 py-2.5 text-lg font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 text-lg font-bold text-white bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 rounded-lg transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">Simpan Perubahan</button>
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
                                    <a :href="'/storage/' + infoTambahan.lampiran" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white rounded-lg text-lg font-bold border border-blue-200 transition-all shadow-sm">
                                        📎 Unduh / Lihat File Bukti
                                    </a>
                                </div>

                                <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-5">
                                    <form :action="'/admin/info-tambahan/' + infoTambahan.id" method="POST" onsubmit="return confirm('Yakin ingin menghapus informasi ini secara permanen?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-5 py-2.5 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-600 hover:text-white rounded-lg transition-all shadow-sm">Hapus Info Ini</button>
                                    </form>
                                    <button type="button" @click="showModalInfoTambahan = false" class="px-6 py-2.5 text-lg font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MENU 4: INVESTIGASI -->
                <div x-show="tab === 'investigasi'" x-transition.opacity style="display: none;"
                    x-init="initTable()" x-data="{ ...tableManager('table-investigasi', {{ count($dataKasus) }}),  showModalEditInvestigasi: false, formInvestigasi: { id: '', fakta_lapangan: '', pihak_terlibat: '', kesimpulan: '', investigator_id: '', bukti_investigasi_url: '', delete_bukti_investigasi: 0 } }">
                    <div class="px-6 py-4 border-b border-slate-200 bg-white flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-800">Data Kertas Kerja Investigasi</h3>
                        <a href="{{ route('admin.rekap.cetak', 'investigasi') }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all transform hover:scale-105">
                            🖨️ Cetak Rekap
                        </a>
                    </div>
                    
    <div class="px-4 py-2.5 bg-white border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="flex items-center gap-1.5 font-medium">
            <span>Tampilkan</span>
            <select x-model="perPage" @change="updateTable()" class="bg-white border border-slate-300 rounded px-2.5 py-1 text-xs font-semibold focus:border-bjm-gold outline-none">
                <option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50">50</option>
            </select>
            <span>data</span>
        </div>
        <div class="flex items-center gap-2">
            <label class="font-semibold text-slate-600">Cari Data:</label>
            <input type="text" x-model="search" @input="updateTable()" placeholder="Ketik untuk mencari..." class="bg-white border border-slate-300 rounded px-3 py-1.5 text-xs focus:border-bjm-gold outline-none w-52 sm:w-64">
        </div>
    </div>
    <div class="overflow-x-auto">
                        <table id="table-investigasi" class="w-full text-left border-collapse border border-slate-200">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-investigasi', 0)">Kode Kasus <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-investigasi', 1)">Investigator Lapangan <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-investigasi', 2)">Fakta Temuan <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-investigasi', 3)">Aksi <span class="text-slate-400">⇅</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @forelse($dataInvestigasi as $i)
                                <tr data-row="true" class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition">
                                    <td  class="px-3.5 py-2.5 font-mono font-bold text-slate-600 border border-slate-200">{{ $i->kode_tiket }}</td>
                                    <td  class="px-3.5 py-2.5 text-slate-800 font-medium border border-slate-200">{{ $i->investigator->name ?? 'Tim Lapangan' }}</td>
                                    <td  class="px-3.5 py-2.5 text-slate-600 italic border border-slate-200">"{{ Str::limit($i->fakta_lapangan ?? $i->hasil_investigasi, 40) }}"</td>
                                    <td  class="px-3.5 py-2.5 text-center border border-slate-200">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.show', $i->id) }}" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-md transition-all shadow-xs" title="Lihat Berkas Lengkap">
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
                                            }' class="p-1.5 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-md transition-all shadow-xs" title="Edit Investigasi">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('admin.investigasi.destroy', $i->id) }}" method="POST" class="inline" onsubmit="return confirm('Reset hasil investigasi ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-md transition-all shadow-xs" title="Reset">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4"  class="p-8 text-center text-slate-500 italic border border-slate-200">Belum ada data hasil investigasi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
    <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="font-medium">
            Menampilkan <span class="font-bold text-slate-800" x-text="startIndex"></span> sampai <span class="font-bold text-slate-800" x-text="endIndex"></span> dari <span class="font-bold text-slate-800" x-text="filteredCount"></span> total data
        </div>
        <div class="flex items-center gap-1">
            <button type="button" @click="prevPage()" :disabled="currentPage <= 1" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Sebelumnya</button>
            <template x-for="p in totalPages" :key="p">
                <button type="button" @click="goToPage(p)" :class="currentPage === p ? 'bg-amber-500 text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-100'" class="px-3 py-1 border border-slate-300 rounded text-xs transition" x-text="p"></button>
            </template>
            <button type="button" @click="nextPage()" :disabled="currentPage >= totalPages" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Selanjutnya</button>
        </div>
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
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Investigator Lapangan <span class="text-red-500">*</span></label>
                                        <select name="investigator_id" x-model="formInvestigasi.investigator_id" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none">
                                            <option value="">-- Pilih Investigator --</option>
                                            @foreach($dataPegawai->where('peran', 'investigator') as $inv)
                                                <option value="{{ $inv->id }}">{{ $inv->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Fakta di Lapangan <span class="text-red-500">*</span></label>
                                        <textarea name="fakta_lapangan" x-model="formInvestigasi.fakta_lapangan" rows="6" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Pihak Terlibat / Saksi <span class="text-red-500">*</span></label>
                                        <textarea name="pihak_terlibat" x-model="formInvestigasi.pihak_terlibat" rows="4" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Kesimpulan Akhir & Rekomendasi <span class="text-red-500">*</span></label>
                                        <textarea name="kesimpulan" x-model="formInvestigasi.kesimpulan" rows="6" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 focus:border-bjm-gold outline-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-lg font-bold text-slate-700 mb-1">Lampiran Bukti Temuan Lapangan (Gambar/PDF)</label>
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
                                    <button type="button" @click="showModalEditInvestigasi = false" class="px-5 py-2.5 text-lg font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 text-lg font-bold text-white bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 rounded-lg transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">Simpan Koreksi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- MENU 5: TINDAK LANJUT -->
                <div x-show="tab === 'tindaklanjut'" x-transition.opacity style="display: none;"
                    x-init="initTable()" x-data="{ ...tableManager('table-tindaklanjut', {{ count($dataTindakLanjut) }}),  showModalEditTindakLanjut: false, formTindakLanjut: { id: '', pihak_penindak: '', tanggal_tindak_lanjut: '', tindak_lanjut: '' } }">
                    <div class="px-6 py-4 border-b border-slate-200 bg-white shadow-sm flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Arsip Keputusan & Tindak Lanjut</h3>
                            <p class="text-xs text-slate-500">Daftar kasus pegawai yang telah selesai diproses eksekusi keputusannya.</p>
                        </div>
                        <a href="{{ route('admin.rekap.cetak', 'tindaklanjut') }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all transform hover:scale-105">
                            🖨️ Cetak Rekap
                        </a>
                    </div>

    <div class="px-4 py-2.5 bg-white border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="flex items-center gap-1.5 font-medium">
            <span>Tampilkan</span>
            <select x-model="perPage" @change="updateTable()" class="bg-white border border-slate-300 rounded px-2.5 py-1 text-xs font-semibold focus:border-bjm-gold outline-none">
                <option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50">50</option>
            </select>
            <span>data</span>
        </div>
        <div class="flex items-center gap-2">
            <label class="font-semibold text-slate-600">Cari Data:</label>
            <input type="text" x-model="search" @input="updateTable()" placeholder="Ketik untuk mencari..." class="bg-white border border-slate-300 rounded px-3 py-1.5 text-xs focus:border-bjm-gold outline-none w-52 sm:w-64">
        </div>
    </div>
                    <div class="overflow-x-auto">
                        <table id="table-tindaklanjut" class="w-full text-left border-collapse border border-slate-200">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-tindaklanjut', 0)">Kode Kasus <span class="text-slate-400">⇅</span></th>
                                    <th class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-tindaklanjut', 1)">Judul Laporan <span class="text-slate-400">⇅</span></th>
                                    <th class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-tindaklanjut', 2)">Tingkat <span class="text-slate-400">⇅</span></th>
                                    <th class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-tindaklanjut', 3)">Instansi Penindak <span class="text-slate-400">⇅</span></th>
                                    <th class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-tindaklanjut', 4)">Tanggal Eksekusi <span class="text-slate-400">⇅</span></th>
                                    <th class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-tindaklanjut', 5)">Aksi <span class="text-slate-400">⇅</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @forelse($dataTindakLanjut as $dt)
                                <tr data-row="true" class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition">
                                    <td class="px-3.5 py-2.5 font-mono font-bold text-slate-700 border border-slate-200">{{ $dt->kode_tiket }}</td>
                                    <td class="px-3.5 py-2.5 text-slate-600 border border-slate-200">{{ Str::limit($dt->judul_laporan, 40) }}</td>
                                    <td class="px-3.5 py-2.5 border border-slate-200">
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
                                    <td class="px-3.5 py-2.5 text-slate-700 font-medium border border-slate-200">{{ $dt->pihak_penindak ?? '-' }}</td>
                                    <td class="px-3.5 py-2.5 text-slate-600 border border-slate-200">
                                        {{ $dt->tanggal_tindak_lanjut ? \Carbon\Carbon::parse($dt->tanggal_tindak_lanjut)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-3.5 py-2.5 text-center border border-slate-200">
                                        <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                            <a href="{{ route('admin.show', $dt->id) }}" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-md transition-all shadow-xs" title="Lihat Detail Berkas">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <button @click='showModalEditTindakLanjut = true; formTindakLanjut = { 
                                                id: {{ $dt->id }}, 
                                                judul_laporan: {{ json_encode($dt->judul_laporan) }},
                                                kategori_id: {{ json_encode($dt->kategori_id) }},
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
                                            }' class="p-1.5 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-md transition-all shadow-xs" title="Edit Keputusan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('admin.tindaklanjut.destroy', $dt->id) }}" method="POST" class="inline" onsubmit="return confirm('Batalkan keputusan ini? Status kasus akan kembali ke tahap Investigasi.');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-md transition-all shadow-xs" title="Batalkan Keputusan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="p-8 text-center text-slate-500 italic border border-slate-200">Belum ada data tindak lanjut yang diinput.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
    <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="font-medium">
            Menampilkan <span class="font-bold text-slate-800" x-text="startIndex"></span> sampai <span class="font-bold text-slate-800" x-text="endIndex"></span> dari <span class="font-bold text-slate-800" x-text="filteredCount"></span> total data
        </div>
        <div class="flex items-center gap-1">
            <button type="button" @click="prevPage()" :disabled="currentPage <= 1" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Sebelumnya</button>
            <template x-for="p in totalPages" :key="p">
                <button type="button" @click="goToPage(p)" :class="currentPage === p ? 'bg-amber-500 text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-100'" class="px-3 py-1 border border-slate-300 rounded text-xs transition" x-text="p"></button>
            </template>
            <button type="button" @click="nextPage()" :disabled="currentPage >= totalPages" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Selanjutnya</button>
        </div>
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
                                            <select name="kategori_id" x-model="formTindakLanjut.kategori_id" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-bjm-gold outline-none">
                                                <option value="">-- Pilih Klasifikasi Terdekat --</option>
                                                @foreach($kategoris as $kategori)
                                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                                @endforeach
                                            </select>
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
                                    <button type="button" @click="showModalEditTindakLanjut = false" class="px-5 py-2.5 text-lg font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 text-lg font-bold text-white bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 rounded-lg transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">Perbarui Keputusan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- MENU 6: BUKTI -->
                <div x-show="tab === 'bukti'" x-transition.opacity style="display: none;"
                    x-init="initTable()" x-data="{ ...tableManager('table-bukti', {{ count($dataKasus) }}),  showModalEditBukti: false, formBukti: { id: '', kode_tiket: '', lampiran_bukti_url: '', lampiran_susulan_url: '', bukti_investigasi_url: '', delete_lampiran_bukti: 0, delete_lampiran_susulan: 0, delete_bukti_investigasi: 0 } }">
                    <div class="px-6 py-4 border-b border-slate-200 bg-white shadow-sm flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Manajemen Data Bukti</h3>
                            <p class="text-xs text-slate-500">Pusat kontrol file fisik temuan pelanggaran pegawai.</p>
                        </div>
                        <a href="{{ route('admin.rekap.cetak', 'bukti') }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all transform hover:scale-105">
                            🖨️ Cetak Rekap
                        </a>
                    </div>
                    
    <div class="px-4 py-2.5 bg-white border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="flex items-center gap-1.5 font-medium">
            <span>Tampilkan</span>
            <select x-model="perPage" @change="updateTable()" class="bg-white border border-slate-300 rounded px-2.5 py-1 text-xs font-semibold focus:border-bjm-gold outline-none">
                <option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50">50</option>
            </select>
            <span>data</span>
        </div>
        <div class="flex items-center gap-2">
            <label class="font-semibold text-slate-600">Cari Data:</label>
            <input type="text" x-model="search" @input="updateTable()" placeholder="Ketik untuk mencari..." class="bg-white border border-slate-300 rounded px-3 py-1.5 text-xs focus:border-bjm-gold outline-none w-52 sm:w-64">
        </div>
    </div>
    <div class="overflow-x-auto">
                        <table id="table-bukti" class="w-full text-left border-collapse border border-slate-200">
                            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-bukti', 0)">Kode Kasus <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-bukti', 1)">Bukti Awal <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-bukti', 2)">Bukti Tambahan <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-bukti', 3)">Bukti Investigasi <span class="text-slate-400">⇅</span></th>
                                    <th  class="px-3.5 py-2.5 text-[11px] uppercase font-bold tracking-wider border border-slate-200 cursor-pointer hover:bg-slate-200 transition" onclick="sortTable('table-bukti', 4)">Aksi <span class="text-slate-400">⇅</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @forelse($dataBukti as $db)
                                <tr data-row="true" class="odd:bg-white even:bg-slate-50/60 hover:bg-slate-50 transition">
                                    <td  class="px-3.5 py-2.5 font-mono font-bold text-slate-700 border border-slate-200">{{ $db->kode_tiket }}</td>
                                    <td  class="px-3.5 py-2.5 border border-slate-200">
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
                                    <td  class="px-3.5 py-2.5 border border-slate-200">
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
                                    <td  class="px-3.5 py-2.5 border border-slate-200">
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
                                    <td  class="px-3.5 py-2.5 text-center flex justify-center items-center gap-2 border border-slate-200">
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
                                <tr><td colspan="5"  class="p-8 text-center text-slate-500 italic border border-slate-200">Tidak ada berkas bukti yang terlampir.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
    <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
        <div class="font-medium">
            Menampilkan <span class="font-bold text-slate-800" x-text="startIndex"></span> sampai <span class="font-bold text-slate-800" x-text="endIndex"></span> dari <span class="font-bold text-slate-800" x-text="filteredCount"></span> total data
        </div>
        <div class="flex items-center gap-1">
            <button type="button" @click="prevPage()" :disabled="currentPage <= 1" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Sebelumnya</button>
            <template x-for="p in totalPages" :key="p">
                <button type="button" @click="goToPage(p)" :class="currentPage === p ? 'bg-amber-500 text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-100'" class="px-3 py-1 border border-slate-300 rounded text-xs transition" x-text="p"></button>
            </template>
            <button type="button" @click="nextPage()" :disabled="currentPage >= totalPages" class="px-3 py-1 border border-slate-300 rounded bg-white hover:bg-slate-100 disabled:opacity-50">Selanjutnya</button>
        </div>
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
                                    <label class="block text-lg font-bold text-slate-700 mb-1">Bukti Awal Pelapor</label>
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
                                    <label class="block text-lg font-bold text-slate-700 mb-1">Bukti Tambahan Pelapor / Admin</label>
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
                                    <label class="block text-lg font-bold text-slate-700 mb-1">Bukti Temuan Investigasi</label>
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
                                    <button type="button" @click="showModalEditBukti = false" class="px-5 py-2.5 text-lg font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 text-lg font-bold text-white bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 rounded-lg transition-all transform hover:scale-105 shadow-md shadow-amber-500/20">Simpan Berkas</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <!-- MENU 8: LAPORAN & REKAP (CETAK REKAPITULASI) -->
                <div x-show="tab === 'laporan'" x-transition.opacity style="display: none;">
                    <div class="px-6 py-4 border-b border-slate-200 bg-white">
                        <h3 class="text-lg font-bold text-slate-800">Cetak Rekapitulasi Laporan</h3>
                        <p class="text-xs text-slate-500 mt-1">Unduh atau cetak berkas rekapitulasi data sistem secara terpusat.</p>
                    </div>
                    
                    <div class="p-6 bg-slate-50/50 space-y-8">
                        
                        <!-- SECTION: MASTER DATA -->
                        <div>
                            <div class="flex items-center gap-4 mb-5">
                                <h4 class="text-lg font-bold text-slate-500 uppercase tracking-wider">Cetak Master Data</h4>
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
                                <h4 class="text-lg font-bold text-slate-500 uppercase tracking-wider">Cetak Data Pengaduan</h4>
                                <div class="flex-1 h-px bg-slate-300"></div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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

                                <!-- Card: Data Kategori -->
                                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="p-2.5 bg-rose-50 text-rose-600 rounded-lg text-lg">
                                                📑
                                            </div>
                                            <h4 class="font-bold text-slate-800 text-sm">Data Kategori</h4>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-relaxed mb-5">Rekapitulasi agregasi seluruh kategori pelanggaran beserta rincian status penanganannya.</p>
                                    </div>
                                    <a href="{{ route('admin.rekap.cetak', 'kategori') }}" target="_blank" class="w-full text-center inline-flex justify-center items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                        🖨️ Cetak Rekap Kategori
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