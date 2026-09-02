<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; line-height: 1.4; color: #333; }
        
        /* STYLE KHUSUS KOP SURAT */
        .kop-surat { width: 100%; padding-bottom: 5px; border-collapse: collapse; }
        .kop-surat td { border: none; padding: 0; vertical-align: middle; }
        .kop-surat h2 { font-size: 13px; margin: 0; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px; }
        .kop-surat h1 { font-size: 15px; margin: 2px 0; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px; white-space: nowrap; }
        .kop-surat p { font-size: 9.5px; margin: 1px 0 0 0; color: #333; line-height: 1.3; }
        .garis-kop { border-top: 2.5px solid #000; border-bottom: 1px solid #000; height: 1.5px; margin-bottom: 20px; margin-top: 4px; }

        .title { text-align: center; font-weight: bold; font-size: 12px; margin-bottom: 15px; text-transform: uppercase; text-decoration: underline; }
        
        /* STYLE TABEL DATA REKAP */
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th, table.data-table td { padding: 7px 6px; border: 1px solid #333; text-align: left; vertical-align: top; }
        table.data-table th { background-color: #f2f2f2; font-weight: bold; text-align: center; text-transform: uppercase; font-size: 9.5px; }
        
        .center { text-align: center; }
        .ttd-box { page-break-inside: avoid; margin-top: 25px; width: 100%; border-collapse: collapse; }
        .ttd-box td { border: none; }
        
        @if($kategori == 'master_pegawai')
            @page { size: A4 landscape; }
        @else
            @page { size: A4 portrait; }
        @endif
    </style>
</head>
<body>

    <table class="kop-surat">
        <tr>
            <td style="width: 10%; text-align: center;">
                <img src="{{ public_path('images/logo-bjm.png') }}" alt="Logo Banjarmasin" style="width: 60px; height: auto;">
            </td>
            <td style="width: 90%; text-align: center; padding-right: 10%;">
                <h2>PEMERINTAH KOTA BANJARMASIN</h2>
                <h1>MANAJEMEN PELANGGARAN DAN PELAPORAN PEGAWAI</h1>
                <p>Alamat : Jalan R. E. Martadinata No. 1 - Banjarmasin 70111<br>
                Website : banjarmasinkota.go.id, Email : inspektorat@banjarmasinkota.go.id</p>
            </td>
        </tr>
    </table>
    <div class="garis-kop"></div>

    <div class="title">{{ $title }}</div>

    @if($kategori == 'pegawai')
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 35%;">Nama Pengguna / Akses</th>
                    <th style="width: 35%;">Alamat Email (Login)</th>
                    <th style="width: 25%;">Peran Sistem</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $d)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td><strong>{{ $d->name }}</strong></td>
                        <td>{{ $d->email }}</td>
                        <td class="center">{{ strtoupper(str_replace('_', ' ', $d->peran)) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="center" style="font-style: italic; color: #777; padding: 15px;">Belum ada akun pengawas terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @elseif($kategori == 'pengguna')
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 35%;">Nama Pelapor</th>
                    <th style="width: 35%;">Alamat Email</th>
                    <th style="width: 25%;">Tanggal Bergabung</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $d)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td><strong>{{ $d->name }}</strong></td>
                        <td>{{ $d->email }}</td>
                        <td class="center">{{ \Carbon\Carbon::parse($d->created_at)->locale('id')->isoFormat('D MMMM Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="center" style="font-style: italic; color: #777; padding: 15px;">Belum ada pelapor yang mendaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @elseif($kategori == 'master_pegawai')
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 3%; font-size: 8px;">No</th>
                    <th style="width: 11%; font-size: 8px;">NIP</th>
                    <th style="width: 14%; font-size: 8px;">Nama</th>
                    <th style="width: 9%; font-size: 8px;">Jenis Kelamin</th>
                    <th style="width: 10%; font-size: 8px;">TTL</th>
                    <th style="width: 16%; font-size: 8px;">Alamat</th>
                    <th style="width: 9%; font-size: 8px;">Status</th>
                    <th style="width: 11%; font-size: 8px;">Instansi</th>
                    <th style="width: 10%; font-size: 8px;">Jabatan</th>
                    <th style="width: 7%; font-size: 8px;">Akun</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $d)
                    <tr>
                        <td class="center" style="font-size: 9px;">{{ $index + 1 }}</td>
                        <td style="font-size: 9px; color: #444;">{{ $d->nip }}</td>
                        <td style="font-size: 9px;"><strong>{{ $d->nama_pegawai }}</strong></td>
                        <td style="font-size: 9px;">{{ $d->jenis_kelamin ?? '-' }}</td>
                        <td style="font-size: 9px;">{{ $d->tempat_lahir ? $d->tempat_lahir . ', ' : '' }}{{ $d->tanggal_lahir ? \Carbon\Carbon::parse($d->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                        <td style="font-size: 9px;">{{ $d->alamat ?? '-' }}</td>
                        <td class="center" style="font-size: 9px;">{{ $d->status_kepegawaian }}</td>
                        <td style="font-size: 9px;">{{ $d->asal_instansi }}</td>
                        <td style="font-size: 9px;">{{ $d->jabatan }}</td>
                        <td class="center" style="font-size: 9px;">
                            @if($d->user)
                                Terhubung
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="center" style="font-style: italic; color: #777; padding: 15px;">Belum ada master data pegawai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @elseif($kategori == 'kategori')
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 30%;">Nama Kategori Pelanggaran</th>
                    <th style="width: 15%;">Total Kasus</th>
                    <th style="width: 50%;">Rincian Status Penanganan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $d)
                    @php
                        $total = $d->pengaduans->count();
                        $selesai = $d->pengaduans->where('status', 'selesai')->count();
                        $investigasi = $d->pengaduans->where('status', 'investigasi')->count();
                        $ditolak = $d->pengaduans->where('status', 'ditolak')->count();
                        $tindak_lanjut = $d->pengaduans->where('status', 'tindak_lanjut')->count();
                        $masuk = $d->pengaduans->whereIn('status', ['masuk', 'verifikasi'])->count();
                    @endphp
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td><strong>{{ $d->nama_kategori }}</strong></td>
                        <td class="center"><strong>{{ $total }}</strong> Kasus</td>
                        <td>
                            - Selesai: <strong>{{ $selesai }}</strong><br>
                            - Menunggu Tindak Lanjut: <strong>{{ $tindak_lanjut }}</strong><br>
                            - Proses Audit/Investigasi: <strong>{{ $investigasi }}</strong><br>
                            - Masuk / Verifikasi: <strong>{{ $masuk }}</strong><br>
                            - Ditolak: <strong>{{ $ditolak }}</strong>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="center" style="font-style: italic; color: #777; padding: 15px;">Belum ada data kategori pelanggaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @else
        <table class="data-table">
            <thead>
                @if($kategori == 'tanggapan')
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 20%;">Kode Kasus</th>
                        <th style="width: 20%;">Nama Pengirim</th>
                        <th style="width: 20%;">Keterangan</th>
                        <th style="width: 35%;">Isi Informasi Tambahan</th>
                    </tr>
                @else
                    <tr>
                        <th style="width: {{ $kategori == 'investigasi' ? '4%' : '5%' }};">No</th>
                        <th style="width: {{ $kategori == 'investigasi' ? '11%' : '15%' }};">Kode Kasus</th>
                        <th style="width: {{ $kategori == 'investigasi' ? '15%' : '25%' }};">Judul Laporan / Kasus</th>
                        @if($kategori == 'kasus')
                            <th style="width: 15%;">Nama Pelapor</th>
                            <th style="width: 12%;">Tgl Masuk</th>
                            <th style="width: 15%;">Tingkat</th>
                            <th style="width: 13%;">Status</th>
                        @elseif($kategori == 'investigasi')
                            <th style="width: 30%;">Fakta Lapangan</th>
                            <th style="width: 12%;">Pihak Terkait / Saksi</th>
                            <th style="width: 28%;">Kesimpulan Akhir</th>
                        @elseif($kategori == 'tindaklanjut')
                            <th style="width: 25%;">Instansi Penindak</th>
                            <th style="width: 30%;">Sanksi / Keputusan Final</th>
                        @elseif($kategori == 'bukti')
                            <th style="width: 18%;">Bukti Awal</th>
                            <th style="width: 18%;">Bukti Tambahan</th>
                            <th style="width: 19%;">Bukti Investigasi</th>
                        @endif
                    </tr>
                @endif
            </thead>
            <tbody>
                @forelse($data as $index => $d)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        @if($kategori == 'tanggapan')
                            <td style="font-family: monospace; font-weight: bold;">{{ $d->kode_tiket }}</td>
                            <td>{{ $d->user->name ?? $d->nama_pelapor ?? 'Pelapor' }}</td>
                            <td>Informasi Susulan</td>
                            <td><em>"{{ $d->pesan_susulan }}"</em></td>
                        @else
                            <td style="font-family: monospace; font-weight: bold;">{{ $d->kode_tiket }}</td>
                            <td>{{ $d->judul_laporan }}</td>
                            
                            @if($kategori == 'kasus')
                                <td>{{ $d->user->name ?? 'Anonim' }}</td>
                                <td class="center">{{ \Carbon\Carbon::parse($d->created_at)->format('d/m/Y') }}</td>
                                <td class="center" style="font-weight: bold;">{{ $d->tingkat_pelanggaran ?? '-' }}</td>
                                <td class="center" style="font-weight: bold; text-transform: uppercase;">{{ $d->status }}</td>
                            @elseif($kategori == 'investigasi')
                                <td>{{ $d->fakta_lapangan ?? '-' }}</td>
                                <td>{{ $d->pihak_terlibat ?? '-' }}</td>
                                <td>{{ $d->kesimpulan ?? '-' }}</td>
                            @elseif($kategori == 'tindaklanjut')
                                <td>{{ $d->pihak_penindak ?? '-' }}</td>
                                <td>{{ $d->tindak_lanjut ?? '-' }}</td>
                            @elseif($kategori == 'bukti')
                                @php
                                    $extBukti = $d->lampiran_bukti ? strtolower(pathinfo($d->lampiran_bukti, PATHINFO_EXTENSION)) : '';
                                    $extSusulan = $d->lampiran_susulan ? strtolower(pathinfo($d->lampiran_susulan, PATHINFO_EXTENSION)) : '';
                                    $extInvestigasi = $d->bukti_investigasi ? strtolower(pathinfo($d->bukti_investigasi, PATHINFO_EXTENSION)) : '';

                                    $labelBukti = '[-] Tidak Ada';
                                    if ($d->lampiran_bukti) {
                                        if ($extBukti === 'pdf') $labelBukti = '[ADA] Tersedia PDF';
                                        elseif (in_array($extBukti, ['doc', 'docx'])) $labelBukti = '[ADA] Tersedia Word';
                                        elseif (in_array($extBukti, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) $labelBukti = '[ADA] Tersedia Foto';
                                        else $labelBukti = '[ADA] Tersedia File';
                                    }

                                    $labelSusulan = '[-] Tidak Ada';
                                    if ($d->lampiran_susulan) {
                                        if ($extSusulan === 'pdf') $labelSusulan = '[ADA] Tersedia PDF';
                                        elseif (in_array($extSusulan, ['doc', 'docx'])) $labelSusulan = '[ADA] Tersedia Word';
                                        elseif (in_array($extSusulan, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) $labelSusulan = '[ADA] Tersedia Foto';
                                        else $labelSusulan = '[ADA] Tersedia File';
                                    }

                                    $labelInvestigasi = '[-] Tidak Ada';
                                    if ($d->bukti_investigasi) {
                                        if ($extInvestigasi === 'pdf') $labelInvestigasi = '[ADA] Tersedia PDF';
                                        elseif (in_array($extInvestigasi, ['doc', 'docx'])) $labelInvestigasi = '[ADA] Tersedia Word';
                                        elseif (in_array($extInvestigasi, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) $labelInvestigasi = '[ADA] Tersedia Foto';
                                        else $labelInvestigasi = '[ADA] Tersedia File';
                                    }
                                @endphp
                                <td class="center" style="font-weight: bold;">{{ $labelBukti }}</td>
                                <td class="center" style="font-weight: bold;">{{ $labelSusulan }}</td>
                                <td class="center" style="font-weight: bold;">{{ $labelInvestigasi }}</td>
                            @endif
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="center" style="font-style: italic; color: #777; padding: 15px;">Belum ada arsip data untuk kategori rekapitulasi ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <table class="ttd-box">
        <tr>
            <td style="width: 55%;"></td>
            <td style="width: 45%; text-align: center; font-size: 11px; line-height: 1.5;">
                Banjarmasin, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}<br>
                Mengetahui,<br>
                <strong>Kepala Inspektorat Kota Banjarmasin</strong><br><br><br><br><br>
                <strong style="text-decoration: underline;">Drs. Dolly Syahbana, MM</strong><br>
                NIP. 196606011986021009
            </td>
        </tr>
    </table>

</body>
</html>