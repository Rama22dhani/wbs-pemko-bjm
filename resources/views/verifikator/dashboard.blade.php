<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Verifikator - Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai Pemerintah Kota Banjarmasin</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
                get totalPages() {
                    return Math.ceil(this.filteredCount / this.perPage) || 1;
                },
                get startIndex() {
                    if (this.filteredCount === 0) return 0;
                    return (this.currentPage - 1) * this.perPage + 1;
                },
                get endIndex() {
                    return Math.min(this.currentPage * this.perPage, this.filteredCount);
                },
                initTable() {
                    this.updateTable();
                },
                updateTable() {
                    const table = document.getElementById(this.tableId);
                    if (!table) return;
                    const rows = Array.from(table.querySelectorAll('tbody tr[]'));
                    const s = this.search.toLowerCase().trim();

                    let matchedRows = [];
                    rows.forEach(row => {
                        const text = row.innerText.toLowerCase();
                        if (!s || text.includes(s)) {
                            matchedRows.push(row);
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    this.filteredCount = matchedRows.length;
                    if (this.currentPage > this.totalPages) {
                        this.currentPage = this.totalPages || 1;
                    }
                    if (this.currentPage < 1) this.currentPage = 1;

                    const start = (this.currentPage - 1) * this.perPage;
                    const end = start + parseInt(this.perPage);

                    matchedRows.forEach((row, idx) => {
                        if (idx >= start && idx < end) {
                            row.style.display = '';
                            const noCell = row.querySelector('.col-no');
                            if (noCell) noCell.textContent = (idx + 1);
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const emptyRow = table.querySelector('.row-empty-search');
                    if (emptyRow) {
                        emptyRow.style.display = (matchedRows.length === 0 && this.totalItems > 0) ? '' : 'none';
                    }
                },
                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                        this.updateTable();
                    }
                },
                prevPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        this.updateTable();
                    }
                },
                goToPage(p) {
                    this.currentPage = p;
                    this.updateTable();
                }
            };
        }

        // Helper Sort Column on Header Click
        function sortTable(tableId, colIndex) {
            const table = document.getElementById(tableId);
            if (!table) return;
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr[]'));
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

            // Perbarui tampilan pagination & nomor
            const container = table.closest('[x-data]');
            if (container && container._x_dataStack && container._x_dataStack[0]) {
                container._x_dataStack[0].updateTable();
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex flex-col"
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

    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-sm border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14">
                <!-- Brand & Logo -->
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo-bjm.png') }}" alt="Logo Banjarmasin" class="h-8 w-auto object-contain">
                    <div class="leading-tight">
                        <span class="text-sm sm:text-base font-extrabold text-slate-900 block">Portal Verifikator</span>
                        <p class="text-[10px] text-slate-500 font-medium">Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai • Kota Banjarmasin</p>
                    </div>
                </div>

                <!-- User Profile & Logout -->
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block text-right leading-tight">
                        <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] font-semibold text-amber-600">Tim Verifikator Kasus</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-slate-900 text-amber-400 border border-amber-500/40 flex items-center justify-center font-bold text-xs shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="w-px h-5 bg-slate-200 hidden sm:block"></div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="text-xs font-semibold text-slate-500 hover:text-red-600 transition-colors flex items-center gap-1 px-2.5 py-1 rounded-md hover:bg-red-50" title="Keluar dari Portal">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="hidden md:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Workspace -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-5 space-y-5">

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 px-4 py-2.5 rounded-r-lg shadow-sm flex items-center gap-2.5 font-bold text-emerald-800 text-xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 px-4 py-2.5 rounded-r-lg shadow-sm flex items-center gap-2.5 font-bold text-red-800 text-xs">
                <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Hero Header Section -->
        <div class="bg-gradient-to-r from-slate-950 via-bjm-dark to-slate-900 rounded-2xl p-5 sm:p-6 text-white relative overflow-hidden shadow-lg border-b-2 border-bjm-gold">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-60 h-60 bg-bjm-gold rounded-full blur-3xl opacity-15 pointer-events-none"></div>
            <div class="absolute bottom-0 left-10 -mb-16 w-48 h-48 bg-amber-500 rounded-full blur-3xl opacity-10 pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
                <!-- Welcome & Intro -->
                <div class="max-w-xl space-y-2">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30 text-[10px] font-bold uppercase tracking-wider">
                        <span>🛡️</span> Meja Kerja Pengawasan & Verifikasi
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight leading-snug">
                        Selamat datang, {{ explode(' ', Auth::user()->name)[0] }}! 👋
                    </h2>
                    <p class="text-slate-300 text-xs sm:text-[13px] leading-relaxed">
                        Saring kelayakan pengaduan masuk, disposisikan ke tim investigator, serta tetapkan putusan sanksi akhir tindak lanjut kasus ASN.
                    </p>

                    <!-- Navigation Tabs -->
                    <div class="pt-2 flex flex-wrap gap-2">
                        <!-- Tab 1: Antrean Verifikasi -->
                        <button @click="tab = 'verifikasi'" 
                            :class="tab === 'verifikasi' ? 'bg-gradient-to-r from-bjm-gold to-amber-500 text-white shadow-md shadow-amber-500/30 font-bold' : 'bg-white/10 text-slate-200 hover:bg-white/20 font-medium'"
                            class="px-3.5 py-1.5 rounded-lg text-xs transition-all duration-200 flex items-center gap-1.5 border border-white/10">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Antrean Verifikasi</span>
                            @if($kasusMasuk->count() > 0)
                                <span class="px-1.5 py-0.2 text-[10px] font-black rounded-full bg-white text-slate-900">{{ $kasusMasuk->count() }}</span>
                            @endif
                        </button>

                        <!-- Tab 2: Putusan Tindak Lanjut -->
                        <button @click="tab = 'tindak_lanjut'" 
                            :class="tab === 'tindak_lanjut' ? 'bg-gradient-to-r from-bjm-gold to-amber-500 text-white shadow-md shadow-amber-500/30 font-bold' : 'bg-white/10 text-slate-200 hover:bg-white/20 font-medium'"
                            class="px-3.5 py-1.5 rounded-lg text-xs transition-all duration-200 flex items-center gap-1.5 border border-white/10">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                            <span>Input Tindak Lanjut</span>
                            @if($kasusPerluTindakLanjut->count() > 0)
                                <span class="px-1.5 py-0.2 text-[10px] font-black rounded-full bg-red-500 text-white animate-pulse">{{ $kasusPerluTindakLanjut->count() }}</span>
                            @endif
                        </button>

                        <!-- Tab 3: Semua Data Kasus -->
                        <button @click="tab = 'riwayat'" 
                            :class="tab === 'riwayat' ? 'bg-gradient-to-r from-bjm-gold to-amber-500 text-white shadow-md shadow-amber-500/30 font-bold' : 'bg-white/10 text-slate-200 hover:bg-white/20 font-medium'"
                            class="px-3.5 py-1.5 rounded-lg text-xs transition-all duration-200 flex items-center gap-1.5 border border-white/10">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            <span>Data Kasus Terverifikasi ({{ $dataKasus->count() }})</span>
                        </button>
                    </div>
                </div>

                <!-- KPI Counter Card -->
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md rounded-xl px-4 py-3.5 border border-white/15 shrink-0 self-start md:self-auto">
                    <div class="p-2 bg-amber-500/20 rounded-lg text-amber-400 border border-amber-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-black text-white leading-none">
                            {{ $kasusMasuk->count() + $kasusPerluTindakLanjut->count() }}
                        </p>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-amber-300 mt-0.5">Total Tugas Menunggu</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================================================================== -->
        <!-- TAB 1: ANTREAN VERIFIKASI (DATA TABLES LENGKAP) -->
        <!-- ==================================================================== -->
        <div x-show="tab === 'verifikasi'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="py-3 px-4 border-b border-slate-200" 
                 >
                
                <!-- Table Header Card Title -->
                <div class="px-5 py-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/75">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                            <h3 class="text-base font-bold text-slate-800">Data Tables - Antrean Verifikasi Laporan Masuk</h3>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Periksa kelengkapan berkas pengaduan awal sebelum didisposisikan ke Investigator.</p>
                    </div>
                    <span class="py-3 px-4 border-b border-slate-200">
                        Total Antrean: <span class="text-amber-600 font-extrabold">{{ $kasusMasuk->count() }} Kasus</span>
                    </span>
                </div>

                
                
                @if($kasusMasuk->isEmpty())
                <div class="py-12 px-6 text-center flex flex-col items-center justify-center">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h4 class="text-base font-bold text-slate-800">Semua Laporan Selesai Diverifikasi!</h4>
                    <p class="text-slate-500 text-xs mt-1 max-w-sm">Saat ini tidak ada laporan baru yang membutuhkan verifikasi. Antrean Anda bersih.</p>
                </div>
                @else
                <!-- Full Bordered Grid Table (Grid Style Seperti Gambar) -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-100 text-slate-700 text-[11px] uppercase font-bold tracking-wider select-none">
                            <tr>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    No 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Kode Kasus 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Tgl Kejadian & Masuk 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Identitas Pelapor 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Kategori 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Lokasi Kejadian 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Uraian Laporan 
                                </th>
                                <th class="py-3 px-4 border-b border-slate-200">
                                    Lampiran Bukti
                                </th>
                                <th class="py-3 px-4 border-b border-slate-200">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-xs divide-y divide-slate-200">
                            @foreach($kasusMasuk as $index => $km)
                            <tr  class="odd:bg-white even:bg-slate-50/60 hover:bg-amber-50/40 transition duration-150">
                                <!-- No -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    {{ $index + 1 }}
                                </td>
                                <!-- Kode Kasus -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    #{{ $km->kode_tiket }}
                                </td>
                                <!-- Tanggal -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    <span class="font-bold text-slate-800 block">{{ $km->created_at->format('d/m/Y') }}</span>
                                    <span class="text-[10px] text-slate-500">Kejadian: {{ $km->tanggal_kejadian ? \Carbon\Carbon::parse($km->tanggal_kejadian)->format('d/m/Y') : '-' }}</span>
                                </td>
                                <!-- Identitas Pelapor -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    @if($km->user_id)
                                        <p class="font-bold text-slate-900 leading-tight">{{ $km->user->name }}</p>
                                        @if($km->nip)
                                            <p class="text-[10px] text-slate-500">NIP: {{ $km->nip }}</p>
                                        @endif
                                        @if($km->nomor_hp)
                                            <p class="text-[10px] text-slate-500">{{ $km->nomor_hp }}</p>
                                        @endif
                                    @else
                                        <span class="py-3 px-4 border-b border-slate-200">
                                            🔒 Anonim
                                        </span>
                                    @endif
                                </td>
                                <!-- Kategori -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    <span class="px-2 py-0.5 bg-amber-50 text-amber-800 border border-amber-200 rounded text-[11px] font-bold">
                                        {{ $km->kategori_laporan }}
                                    </span>
                                </td>
                                <!-- Lokasi Kejadian -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    {{ $km->lokasi_kejadian }}
                                </td>
                                <!-- Uraian Laporan -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    <p class="text-slate-900 font-bold text-xs leading-snug line-clamp-1">{{ $km->judul_laporan }}</p>
                                    <p class="text-slate-500 text-[11px] mt-0.5 line-clamp-2 italic">{{ $km->isi_laporan }}</p>
                                </td>
                                <!-- Lampiran Bukti -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    @if($km->lampiran_bukti)
                                        <a href="{{ asset('storage/' . $km->lampiran_bukti) }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-bold text-[11px] underline">
                                            📎 Bukti Awal
                                        </a>
                                    @else
                                        <span class="text-slate-400 text-[11px] italic">-</span>
                                    @endif
                                    @if($km->lampiran_susulan)
                                        <a href="{{ \Illuminate\Support\Str::startsWith($km->lampiran_susulan, ['bukti_susulan/', 'bukti_pengaduan/']) ? asset('storage/' . $km->lampiran_susulan) : asset('uploads/pengaduan/' . $km->lampiran_susulan) }}" target="_blank" class="block text-cyan-600 hover:text-cyan-800 font-bold text-[10px] underline mt-0.5">
                                            📎 Bukti Susulan
                                        </a>
                                    @endif
                                </td>
                                <!-- Aksi -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Tombol Detail Berkas -->
                                        <a href="{{ route('verifikator.show', $km->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 rounded text-xs font-bold transition shadow-sm" title="Lihat Berkas Lengkap">
                                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            <span>Detail</span>
                                        </a>

                                        <!-- Tombol Verifikasi -->
                                        <button @click='showModalVerifikasi = true; formVerif = {
                                            id: {{ $km->id }},
                                            judul: {{ json_encode($km->judul_laporan) }},
                                            pelapor: {{ json_encode($km->user->name ?? "Anonim") }},
                                            keputusan: "terima",
                                            tingkat_pelanggaran: "",
                                            investigator_id: "",
                                            catatan_verifikator: ""
                                        }' class="inline-flex items-center gap-1 px-3 py-1.5 bg-gradient-to-r from-bjm-gold to-amber-500 hover:from-amber-600 hover:to-amber-600 text-white rounded text-xs font-bold shadow-sm shadow-amber-500/20 hover:-translate-y-0.5 transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            <span>Verifikasi</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            <tr class="row-empty-search" style="display: none;">
                                <td colspan="9" class="py-3 px-4 border-b border-slate-200">
                                    Tidak ada data antrean yang cocok dengan pencarian.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                
                @endif

            </div>
        </div>

        <!-- ==================================================================== -->
        <!-- TAB 2: PUTUSAN TINDAK LANJUT (DATA TABLES LENGKAP) -->
        <!-- ==================================================================== -->
        <div x-show="tab === 'tindak_lanjut'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="py-3 px-4 border-b border-slate-200"
                 >
                
                <!-- Table Header Card Title -->
                <div class="px-5 py-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/75">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span>
                            <h3 class="text-base font-bold text-slate-800">Data Tables - Kasus Menunggu Putusan Akhir / Sanksi</h3>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Laporan investigasi selesai. Tinjau kesimpulan dan fakta lapangan untuk memutuskan sanksi final.</p>
                    </div>
                    <span class="py-3 px-4 border-b border-slate-200">
                        Menunggu Putusan: <span class="text-red-600 font-extrabold">{{ $kasusPerluTindakLanjut->count() }} Kasus</span>
                    </span>
                </div>

                
                
                @if($kasusPerluTindakLanjut->isEmpty())
                <div class="py-12 px-6 text-center flex flex-col items-center justify-center">
                    <div class="w-14 h-14 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h4 class="text-base font-bold text-slate-800">Tidak Ada Kasus Menunggu</h4>
                    <p class="text-slate-500 text-xs mt-1 max-w-sm">Belum ada laporan dari tim investigator yang memerlukan penetapan sanksi tindak lanjut.</p>
                </div>
                @else
                <!-- Full Bordered Grid Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-100 text-slate-700 text-[11px] uppercase font-bold tracking-wider select-none">
                            <tr>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    No 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Kode Kasus 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Kategori & Judul Perkara 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Tgl Selesai Audit 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Tingkat Kasus 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Investigator Lapangan 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Kesimpulan Audit Lapangan 
                                </th>
                                <th class="py-3 px-4 border-b border-slate-200">
                                    Bukti Temuan
                                </th>
                                <th class="py-3 px-4 border-b border-slate-200">
                                    Aksi Penindakan
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-xs divide-y divide-slate-200">
                            @foreach($kasusPerluTindakLanjut as $index => $kpt)
                            <tr  class="odd:bg-white even:bg-slate-50/60 hover:bg-amber-50/40 transition duration-150">
                                <!-- No -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    {{ $index + 1 }}
                                </td>
                                <!-- Kode Kasus -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    #{{ $kpt->kode_tiket }}
                                </td>
                                <!-- Kategori & Judul Perkara -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    <span class="py-3 px-4 border-b border-slate-200">
                                        {{ $kpt->kategori_laporan }}
                                    </span>
                                    <p class="font-bold text-slate-900 leading-snug line-clamp-1">{{ $kpt->judul_laporan }}</p>
                                </td>
                                <!-- Tanggal Selesai Audit -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    <span class="font-bold text-slate-800 block">{{ $kpt->updated_at->format('d/m/Y') }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $kpt->updated_at->format('H:i') }} WITA</span>
                                </td>
                                <!-- Tingkat Kasus -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    @if($kpt->tingkat_pelanggaran)
                                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded border 
                                            {{ $kpt->tingkat_pelanggaran == 'Berat' ? 'bg-red-50 text-red-700 border-red-200' : 
                                            ($kpt->tingkat_pelanggaran == 'Sedang' ? 'bg-amber-50 text-amber-700 border-amber-200' : 
                                            'bg-emerald-50 text-emerald-700 border-emerald-200') }}">
                                            {{ $kpt->tingkat_pelanggaran }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs italic">-</span>
                                    @endif
                                </td>
                                <!-- Investigator Lapangan -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    <p class="font-bold text-slate-800 leading-tight">🕵️ {{ $kpt->investigator->name ?? 'Tim Investigator' }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $kpt->investigator->email ?? '-' }}</p>
                                </td>
                                <!-- Kesimpulan Audit Lapangan -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    <div class="py-3 px-4 border-b border-slate-200">
                                        "{{ $kpt->kesimpulan }}"
                                    </div>
                                </td>
                                <!-- Bukti Temuan -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    @if($kpt->bukti_investigasi)
                                        <a href="{{ asset('storage/' . $kpt->bukti_investigasi) }}" target="_blank" class="inline-flex items-center gap-1 text-purple-600 hover:text-purple-800 font-bold text-[11px] underline">
                                            📷 Bukti Temuan
                                        </a>
                                    @else
                                        <span class="text-slate-400 text-[11px] italic">-</span>
                                    @endif
                                </td>
                                <!-- Aksi -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('verifikator.show', $kpt->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 rounded text-xs font-bold transition shadow-sm" title="Lihat Berkas">
                                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            <span>Berkas</span>
                                        </a>
                                        <a href="{{ route('verifikator.tindaklanjut.edit', $kpt->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-bjm-dark hover:bg-slate-800 text-amber-400 rounded text-xs font-bold shadow-sm hover:-translate-y-0.5 transition-all" title="Ketok Putusan Final Sanksi">
                                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                                            <span>Putusan</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            <tr class="row-empty-search" style="display: none;">
                                <td colspan="9" class="py-3 px-4 border-b border-slate-200">
                                    Tidak ada data tindak lanjut yang cocok dengan pencarian.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                
                @endif

            </div>
        </div>

        <!-- ==================================================================== -->
        <!-- TAB 3: DATA KASUS TERVERIFIKASI (DATA TABLES LENGKAP) -->
        <!-- ==================================================================== -->
        <div x-show="tab === 'riwayat'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="py-3 px-4 border-b border-slate-200"
                 >
                
                <!-- Table Header Card Title -->
                <div class="px-5 py-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/75">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span>
                            <h3 class="text-base font-bold text-slate-800">Data Tables - Semua Kasus Terverifikasi</h3>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar lengkap seluruh pengaduan yang telah diproses verifikator beserta progres status terkini.</p>
                    </div>
                    <span class="py-3 px-4 border-b border-slate-200">
                        Total Terverifikasi: <span class="text-blue-600 font-extrabold">{{ $dataKasus->count() }} Kasus</span>
                    </span>
                </div>

                
                
                @if($dataKasus->isEmpty())
                <div class="py-12 px-6 text-center text-slate-400 text-xs">Belum ada data kasus yang diverifikasi.</div>
                @else
                <!-- Full Bordered Grid Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-100 text-slate-700 text-[11px] uppercase font-bold tracking-wider select-none">
                            <tr>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    No 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Kode Kasus 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Tanggal Masuk 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Pelapor 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Kategori 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Lokasi Kejadian 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Judul Laporan 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Tingkat Kasus 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Investigator 
                                </th>
                                <th  class="py-3 px-4 border-b border-slate-200">
                                    Status Progres 
                                </th>
                                <th class="py-3 px-4 border-b border-slate-200">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-xs divide-y divide-slate-200">
                            @foreach($dataKasus as $index => $dk)
                            <tr  class="odd:bg-white even:bg-slate-50/60 hover:bg-amber-50/40 transition duration-150">
                                <!-- No -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    {{ $index + 1 }}
                                </td>
                                <!-- Kode Kasus -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    #{{ $dk->kode_tiket }}
                                </td>
                                <!-- Tanggal Masuk -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    {{ $dk->created_at->format('d/m/Y') }}
                                </td>
                                <!-- Pelapor -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    @if($dk->user_id)
                                        <span class="font-bold text-slate-800">{{ $dk->user->name }}</span>
                                    @else
                                        <span class="py-3 px-4 border-b border-slate-200">Anonim</span>
                                    @endif
                                </td>
                                <!-- Kategori -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    <span class="py-3 px-4 border-b border-slate-200">
                                        {{ $dk->kategori_laporan }}
                                    </span>
                                </td>
                                <!-- Lokasi Kejadian -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    {{ $dk->lokasi_kejadian }}
                                </td>
                                <!-- Judul Laporan -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    <p class="line-clamp-2">{{ $dk->judul_laporan }}</p>
                                </td>
                                <!-- Tingkat Kasus -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    @if($dk->tingkat_pelanggaran)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border 
                                            {{ $dk->tingkat_pelanggaran == 'Berat' ? 'bg-red-50 text-red-700 border-red-200' : 
                                            ($dk->tingkat_pelanggaran == 'Sedang' ? 'bg-amber-50 text-amber-700 border-amber-200' : 
                                            'bg-emerald-50 text-emerald-700 border-emerald-200') }}">
                                            {{ $dk->tingkat_pelanggaran }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs italic">-</span>
                                    @endif
                                </td>
                                <!-- Investigator -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    {{ $dk->investigator->name ?? '-' }}
                                </td>
                                <!-- Status Progres -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    <span class="px-2.5 py-1 text-[10px] uppercase font-bold rounded border 
                                        {{ $dk->status == 'selesai' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 
                                        ($dk->status == 'investigasi' ? 'bg-blue-50 text-blue-700 border-blue-200' : 
                                        ($dk->status == 'ditolak' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-slate-100 text-slate-600 border-slate-200')) }}">
                                        {{ $dk->status }}
                                    </span>
                                </td>
                                <!-- Aksi -->
                                <td class="py-3 px-4 border-b border-slate-200">
                                    <a href="{{ route('verifikator.show', $dk->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 rounded text-xs font-bold transition shadow-sm">
                                        <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <span>Berkas</span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            <tr class="row-empty-search" style="display: none;">
                                <td colspan="11" class="py-3 px-4 border-b border-slate-200">
                                    Tidak ada data kasus yang cocok dengan pencarian.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                
                @endif

            </div>
        </div>

    </main>

    <!-- Modal Panel Verifikasi Kasus -->
    <div x-show="showModalVerifikasi" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 backdrop-blur-sm px-4" style="display: none;" x-transition>
        <div @click.away="showModalVerifikasi = false" class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
            
            <!-- Modal Header: bjm-dark with bjm-gold border -->
            <div class="bg-bjm-dark p-4 border-b-2 border-bjm-gold flex justify-between items-center sticky top-0 z-10 text-white">
                <div class="flex items-center gap-2">
                    <span class="text-lg">🛡️</span>
                    <div>
                        <h3 class="font-bold text-sm sm:text-base leading-tight">Panel Verifikasi Laporan</h3>
                        <p class="text-amber-400 text-[11px] font-medium">Saring kelayakan dan tentukan disposisi</p>
                    </div>
                </div>
                <button @click="showModalVerifikasi = false" class="text-slate-400 hover:text-white font-bold p-1 rounded-md hover:bg-white/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Form Body -->
            <form :action="'/verifikator/kasus/' + formVerif.id + '/verifikasi'" method="POST" class="p-5 overflow-y-auto space-y-3 text-xs">
                @csrf
                @method('PUT')

                <!-- Detail Pelapor & Judul -->
                <div class="py-3 px-4 border-b border-slate-200">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Pelapor: <span class="text-amber-600 font-extrabold" x-text="formVerif.pelapor"></span></span>
                    <p class="text-xs font-extrabold text-slate-800 mt-0.5 line-clamp-2" x-text="formVerif.judul"></p>
                </div>

                <!-- Pilihan Keputusan -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1">Ambil Keputusan Verifikasi <span class="text-red-500">*</span></label>
                    <select name="keputusan" x-model="formVerif.keputusan" class="w-full bg-white border-2 border-slate-300 focus:border-amber-500 focus:ring-0 rounded-lg px-3 py-2 font-bold text-xs outline-none transition cursor-pointer">
                        <option value="terima">🟢 TERIMA LAPORAN & DISPOSISIKAN KE INVESTIGATOR</option>
                        <option value="tolak">🔴 TOLAK LAPORAN & TUTUP KASUS INI</option>
                    </select>
                </div>

                <!-- Input Khusus jika Diterima -->
                <div x-show="formVerif.keputusan === 'terima'" x-transition class="space-y-3 pt-1 border-t border-slate-100">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1">Tingkat Kasus <span class="text-red-500">*</span></label>
                            <select name="tingkat_pelanggaran" x-model="formVerif.tingkat_pelanggaran" :required="formVerif.keputusan === 'terima'" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-xs font-bold focus:border-amber-500 outline-none">
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="Ringan">Ringan (Administrasi/Teguran)</option>
                                <option value="Sedang">Sedang (Etika/Disiplin)</option>
                                <option value="Berat">Berat (Pidana/Korupsi/Pungli)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1">Tugaskan Investigator <span class="text-red-500">*</span></label>
                            <select name="investigator_id" x-model="formVerif.investigator_id" :required="formVerif.keputusan === 'terima'" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-xs font-bold focus:border-amber-500 outline-none">
                                <option value="">-- Pilih Investigator --</option>
                                @foreach($dataPegawai as $inv)
                                    <option value="{{ $inv->id }}">{{ $inv->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Catatan Verifikator -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1" x-text="formVerif.keputusan === 'terima' ? 'Instruksi Khusus untuk Investigator' : 'Alasan Penolakan Laporan'"></label>
                    <textarea name="catatan_verifikator" x-model="formVerif.catatan_verifikator" rows="2.5" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-xs focus:border-amber-500 outline-none" :placeholder="formVerif.keputusan === 'terima' ? 'Contoh: Periksa bukti fisik dan minta klarifikasi pihak terkait...' : 'Contoh: Laporan tidak memenuhi unsur pelanggaran dinas...'"></textarea>
                </div>

                <!-- Modal Actions -->
                <div class="mt-4 flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="showModalVerifikasi = false" class="px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition">Batal</button>
                    <button type="submit" class="px-5 py-2 text-xs font-black text-white rounded-lg shadow-sm transition transform hover:scale-105"
                        :class="formVerif.keputusan === 'terima' ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 shadow-emerald-500/20' : 'bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 shadow-red-500/20'"
                        x-text="formVerif.keputusan === 'terima' ? 'ACC & DISPOSISIKAN' : 'KONFIRMASI TOLAK'">
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
