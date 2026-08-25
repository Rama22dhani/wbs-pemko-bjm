<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dasbor Pelapor - Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bjm-blue': '#0f172a',
                        'bjm-gold': '#d97706',
                        'bjm-gold-hover': '#b45309',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 min-h-screen">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-bjm.png') }}" alt="Pemko Banjarmasin" class="w-10 h-auto">
                
                <div>
                    <h1 class="text-bjm-blue font-bold text-sm md:text-base tracking-wide leading-tight">MANAJEMEN PELANGGARAN DAN PELAPORAN PEGAWAI</h1>
                    <p class="text-slate-500 text-[9px] uppercase font-bold tracking-widest hidden sm:block">PORTAL PELAPORAN PELANGGARAN PEMKO</p>
                </div>
            </div>

                <div class="flex items-center gap-3">
                    <div class="hidden md:flex flex-col items-end mr-1">
                        <span class="text-slate-800 font-bold text-xs">{{ Auth::user()->name }}</span>
                        <span class="text-slate-500 font-medium text-[10px]">{{ Auth::user()->email }}</span>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-slate-100 hover:bg-red-50 text-slate-500 hover:text-red-500 transition p-2 rounded-full shadow-sm" title="Keluar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
        
        <div class="bg-bjm-blue rounded-2xl p-5 md:p-8 shadow-md mb-6 relative overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-5 border-b-4 border-bjm-gold">
            <div class="absolute top-0 right-0 w-48 h-48 bg-bjm-gold/10 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none"></div>
            
            <div class="relative z-10">
                <h2 class="text-xl md:text-3xl font-black text-white mb-1.5">Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h2>
                <p class="text-slate-300 text-sm md:text-base">Pantau proses penindakan kasus Anda atau sampaikan indikasi pelanggaran baru.</p>
            </div>
            
            <a href="{{ route('pelapor.create') }}" 
                class="relative z-10 inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-bold text-white text-sm shadow-md transition transform hover:-translate-y-0.5 w-full md:w-auto bg-bjm-gold hover:bg-amber-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Pengaduan Baru
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-sm flex items-center gap-3">
            <div class="bg-emerald-100 p-1.5 rounded-full text-emerald-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="font-bold text-sm">Berhasil!</p>
                <p class="text-xs">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white border border-slate-200 p-4 rounded-xl shadow-sm flex items-center gap-4">
                <div class="bg-blue-50 p-3 rounded-full text-blue-500 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wide">Total Kasus Anda</p>
                    <p class="text-xl font-black text-slate-800">{{ $laporans->count() }}</p>
                </div>
            </div>

            <div class="bg-white border border-slate-200 p-4 rounded-xl shadow-sm flex items-center gap-4">
                <div class="bg-amber-50 p-3 rounded-full text-amber-500 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wide">Sedang Audit</p>
                    <p class="text-xl font-black text-slate-800">{{ $laporans->whereIn('status', ['pending', 'verifikasi', 'investigasi'])->count() }}</p>
                </div>
            </div>

            <div class="bg-white border border-slate-200 p-4 rounded-xl shadow-sm flex items-center gap-4">
                <div class="bg-emerald-50 p-3 rounded-full text-emerald-500 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wide">Selesai Ditindak</p>
                    <p class="text-xl font-black text-slate-800">{{ $laporans->where('status', 'selesai')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-bjm-blue text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-bjm-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat Pelaporan Kasus & Info Tambahan
                </h3>
            </div>
            
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse whitespace-nowrap md:whitespace-normal">
                    <thead>
                        <tr class="text-slate-500 text-[11px] uppercase font-bold tracking-wider border-b border-slate-200 bg-white">
                            <th class="p-3 pl-5">Kode Kasus</th>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3 min-w-[200px]">Judul Laporan</th>
                            <th class="p-3">Status Audit</th>
                            <th class="p-3 text-center pr-5">Aksi</th>
                        </tr>
                    </thead>
                    @forelse($laporans as $laporan)
                    <tbody x-data="{ openChat: false }" class="border-b border-slate-100 text-sm">
                        <tr class="hover:bg-slate-50 transition duration-150">
                            <td class="p-3 pl-5">
                                <span class="font-mono font-bold text-xs text-bjm-gold bg-amber-50 border border-amber-100 px-2 py-1 rounded shadow-sm">{{ $laporan->kode_tiket }}</span>
                            </td>
                            <td class="p-3 text-slate-600 text-xs">{{ $laporan->created_at->format('d M Y') }}</td>
                            <td class="p-3 font-semibold text-slate-800 text-xs md:text-sm">
                                <span class="truncate block max-w-xs md:max-w-md">{{ $laporan->judul_laporan }}</span>
                            </td>
                            <td class="p-3">
                                @if($laporan->status == 'pending' || $laporan->status == 'masuk')
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Menunggu Verifikasi</span>
                                @elseif($laporan->status == 'investigasi')
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-200">Proses Audit</span>
                                @elseif($laporan->status == 'selesai')
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">Selesai</span>
                                @elseif($laporan->status == 'ditolak')
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-600 border border-red-200">Ditolak</span>
                                @endif
                            </td>
                            <td class="p-3 text-center pr-5 flex items-center justify-center gap-2">
                                <button @click="openChat = !openChat" class="flex items-center gap-1 text-bjm-blue hover:text-white font-bold text-[11px] bg-slate-100 hover:bg-bjm-blue px-2.5 py-1.5 rounded-lg transition shadow-sm border border-slate-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    Tambah Data
                                </button>
                                <a href="{{ route('lacak', ['kode_tiket' => $laporan->kode_tiket]) }}" class="text-blue-600 hover:text-blue-800 font-bold text-[11px] hover:underline transition whitespace-nowrap">Lacak &rarr;</a>
                            </td>
                        </tr>
                        
                        <!-- Panel Formulir Tambah Data yang bisa disembunyikan/ditampilkan -->
                        <tr x-show="openChat" style="display: none;" x-transition class="bg-slate-50 border-t border-slate-100 whitespace-normal">
                            <td colspan="5" class="p-4 md:p-6 shadow-inner">
                                <div class="max-w-2xl mx-auto bg-white rounded-xl border border-slate-200 p-5 md:p-6 shadow-sm">
                                    <h4 class="text-slate-800 font-bold text-sm md:text-base mb-1">
                                        Formulir Informasi Tambahan Kasus: <span class="text-bjm-gold font-mono">{{ $laporan->kode_tiket }}</span>
                                    </h4>
                                    <p class="text-slate-500 text-xs mb-5">Gunakan fasilitas ini untuk melampirkan keterangan baru atau bukti dokumen pendukung tambahan.</p>
                                    
                                    <form action="{{ route('pelapor.informasi.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="kode_tiket" value="{{ $laporan->kode_tiket }}">
                                        
                                        <div class="mb-4">
                                            <label class="block text-[10px] text-slate-600 font-bold uppercase mb-1.5">Lampiran Dokumen Bukti (Opsional)</label>
                                            <input type="file" name="lampiran_bukti" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700 bg-slate-50 border border-slate-300 rounded-lg"/>
                                            <p class="text-[9px] text-slate-400 mt-1">* Format yang didukung: JPG, PNG, PDF, DOC, DOCX. Maksimal 5MB.</p>
                                        </div>

                                        <label class="block text-[10px] text-slate-600 font-bold uppercase mb-1.5">Detail Informasi Tambahan <span class="text-red-500">*</span></label>
                                        <textarea name="isi_pesan" required rows="3" placeholder="Uraikan informasi atau data tambahan yang ingin Anda sertakan pada kasus ini..." 
                                            class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-lg px-3 py-2 focus:border-bjm-gold outline-none transition text-xs mb-5"></textarea>
                                        
                                        <div class="flex justify-end gap-2">
                                            <!-- Tombol Batal untuk menutup panel -->
                                            <button type="button" @click="openChat = false" class="bg-slate-200 hover:bg-slate-300 text-slate-600 px-4 py-2 rounded-lg font-bold text-xs transition">
                                                Batal
                                            </button>
                                            
                                            <button type="submit" class="bg-bjm-gold hover:bg-amber-600 text-white px-5 py-2 rounded-lg font-bold text-xs transition flex items-center gap-2 shadow-sm">
                                                Simpan Data Tambahan
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    @empty
                    <tbody>
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-500">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 border border-slate-200">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="font-bold text-sm text-slate-700">Belum Ada Riwayat Pelaporan Kasus</p>
                                <p class="text-xs mt-1">Anda belum pernah mengirimkan laporan pengaduan pelanggaran pegawai.</p>
                            </td>
                        </tr>
                    </tbody>
                    @endforelse
                </table>
            </div>
        </div>

    </div>

</body>
</html>