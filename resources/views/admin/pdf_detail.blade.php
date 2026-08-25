<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kasus - {{ $pengaduan->kode_tiket }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.5; color: #333; }
        
        /* STYLE KHUSUS KOP SURAT */
        .kop-surat { width: 100%; padding-bottom: 5px; border-collapse: collapse; }
        .kop-surat td { border: none; padding: 0; vertical-align: middle; }
        .kop-surat h2 { font-size: 13px; margin: 0; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px; }
        .kop-surat h1 { font-size: 15px; margin: 2px 0; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px; white-space: nowrap; }
        .kop-surat p { font-size: 9.5px; margin: 1px 0 0 0; color: #333; line-height: 1.3; }
        .garis-kop { border-top: 2.5px solid #000; border-bottom: 1px solid #000; height: 1.5px; margin-bottom: 20px; margin-top: 4px; }

        /* STYLE TABEL DATA KASUS */
        table.data-table { border-collapse: collapse; margin-bottom: 20px; width: 100%; }
        table.data-table th, table.data-table td { padding: 8px; border: 1px solid #ddd; text-align: left; vertical-align: top; }
        table.data-table th { background-color: #f4f4f4; width: 30%; }
        
        .title { text-align: center; font-weight: bold; font-size: 14px; text-decoration: underline; margin-bottom: 20px; }
        .section-title { font-weight: bold; background-color: #333; color: #fff; padding: 5px 10px; margin-top: 20px; margin-bottom: 10px;}
    </style>
</head>
<body>
    
    <!-- KOP SURAT BERLOGO -->
    <table class="kop-surat">
        <tr>
            <td style="width: 10%; text-align: center;">
                <!-- Pemanggilan gambar logo menggunakan public_path() -->
                <img src="{{ public_path('images/logo-bjm.png') }}" alt="Logo Banjarmasin" style="width: 60px; height: auto;">
            </td>
            <!-- padding-right 10% ditambahkan agar teks seimbang dengan logo di kiri -->
            <td style="width: 90%; text-align: center; padding-right: 10%;">
                <h2>PEMERINTAH KOTA BANJARMASIN</h2>
                <h1>MANAJEMEN PELANGGARAN DAN PELAPORAN PEGAWAI</h1>
                <p>Alamat : Jalan R. E. Martadinata No. 1 - Banjarmasin 70111<br>
                Website : banjarmasinkota.go.id, Email : inspektorat@banjarmasinkota.go.id</p>
            </td>
        </tr>
    </table>
    <div class="garis-kop"></div>

    <div class="title">
        BERITA ACARA PEMERIKSAAN KASUS<br>
        Kode Kasus: {{ $pengaduan->kode_tiket }}
    </div>

    <div class="section-title">1. IDENTITAS PELAPOR & DATA KASUS</div>
    <table class="data-table">
        <tr>
            <th>Identitas Pelapor</th>
            <td>{{ $pengaduan->nama_pelapor ?? 'Anonim' }} ({{ $pengaduan->nomor_hp ?? '-' }} | {{ $pengaduan->email ?? '-' }}) {{ $pengaduan->nip ? '- NIP: '.$pengaduan->nip : '' }}</td>
        </tr>
        <tr>
            <th>Judul Laporan</th>
            <td>{{ $pengaduan->judul_laporan }}</td>
        </tr>
        <tr>
            <th>Kategori & Waktu Kejadian</th>
            <td>{{ $pengaduan->kategori_laporan }} | {{ \Carbon\Carbon::parse($pengaduan->tanggal_kejadian)->locale('id')->isoFormat('D MMMM Y') }}</td>
        </tr>
        <tr>
            <th>Lokasi Kejadian</th>
            <td>{{ $pengaduan->lokasi_kejadian }}</td>
        </tr>
        <tr>
            <th>Tingkat Pelanggaran</th>
            <td>{{ $pengaduan->tingkat_pelanggaran ?? 'Belum Ditentukan' }}</td>
        </tr>
        <tr>
            <th>Kronologi Laporan</th>
            <td>{{ $pengaduan->isi_laporan }}</td>
        </tr>
        @if($pengaduan->pesan_susulan)
        <tr>
            <th>Informasi Tambahan Pelapor</th>
            <td><em>"{{ $pengaduan->pesan_susulan }}"</em></td>
        </tr>
        @endif
    </table>

    <div class="section-title">2. HASIL INVESTIGASI LAPANGAN</div>
    <table class="data-table">
        <tr>
            <th>Investigator Lapangan</th>
            <td>{{ $pengaduan->investigator->pegawai->nama_pegawai ?? $pengaduan->investigator->name ?? 'Belum Ditugaskan' }}</td>
        </tr>
        <tr>
            <th>Fakta Lapangan</th>
            <td>{{ $pengaduan->fakta_lapangan ?? '-' }}</td>
        </tr>
        <tr>
            <th>Pihak Terkait / Saksi</th>
            <td>{{ $pengaduan->pihak_terlibat ?? '-' }}</td>
        </tr>
        <tr>
            <th>Kesimpulan & Rekomendasi</th>
            <td>{{ $pengaduan->kesimpulan ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">3. ARSIP BUKTI TERLAMPIR</div>
    <table class="data-table">
        <tr>
            <th>Status Bukti Awal</th>
            <td>{{ $pengaduan->lampiran_bukti ? 'Tersedia [Terlampir di Sistem]' : 'Tidak Ada Bukti Awal' }}</td>
        </tr>
        <tr>
            <th>Status Bukti Tambahan</th>
            <td>{{ $pengaduan->lampiran_susulan ? 'Tersedia [Terlampir di Sistem]' : 'Tidak Ada Bukti Tambahan' }}</td>
        </tr>
        <tr>
            <th>Status Bukti Temuan (Investigasi)</th>
            <td>{{ $pengaduan->bukti_investigasi ? 'Tersedia [Terlampir di Sistem]' : 'Tidak Ada Bukti Investigasi' }}</td>
        </tr>
    </table>

    <div class="section-title">4. KEPUTUSAN & EKSEKUSI TINDAK LANJUT</div>
    <table class="data-table">
        <tr>
            <th>Instansi Penindak</th>
            <td>{{ $pengaduan->pihak_penindak ?? '-' }}</td>
        </tr>
        <tr>
            <th>Tanggal Eksekusi Keputusan</th>
            <td>{{ $pengaduan->tanggal_tindak_lanjut ? \Carbon\Carbon::parse($pengaduan->tanggal_tindak_lanjut)->locale('id')->isoFormat('D MMMM Y') : '-' }}</td>
        </tr>
        <tr>
            <th>Detail Sanksi / Tindak Lanjut</th>
            <td>{{ $pengaduan->tindak_lanjut ?? '-' }}</td>
        </tr>
        <tr>
            <th>Status Kasus Akhir</th>
            <td style="text-transform: uppercase; font-weight: bold;">{{ $pengaduan->status }}</td>
        </tr>
    </table>

    <br><br>
    <!-- TABEL TANDA TANGAN (Tanpa Border) -->
    <table style="border-collapse: collapse; border: none; margin-top: 40px; width: 100%;">
        <tr>
            <td style="border: none; width: 50%; text-align: center; vertical-align: bottom;">
                Investigator Lapangan,<br><br><br><br><br><br>
                <strong><u>{{ $pengaduan->investigator->pegawai->nama_pegawai ?? $pengaduan->investigator->name ?? '_______________________' }}</u></strong><br>
                NIP. {{ $pengaduan->investigator->pegawai->nip ?? $pengaduan->investigator->nip ?? '-' }}
            </td>
            <td style="border: none; width: 50%; text-align: center; vertical-align: bottom;">
                Banjarmasin, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}<br>
                Administrator Sistem,<br><br><br><br><br>
                <strong><u>{{ Auth::user()->pegawai->nama_pegawai ?? Auth::user()->name }}</u></strong><br>
                NIP. {{ Auth::user()->pegawai->nip ?? Auth::user()->nip ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>