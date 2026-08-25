<?php

namespace App\Exports;

use App\Models\Pengaduan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengaduanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Pengaduan::with('investigator')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Tiket',
            'Tanggal Masuk',
            'Nama Pelapor',
            'No. HP',
            'Kategori Laporan',
            'Judul Laporan',
            'Isi Laporan',
            'Tanggal Kejadian',
            'Lokasi Kejadian',
            'Tingkat Pelanggaran',
            'Status',
            'Investigator',
            'Kesimpulan',
            'Pihak Penindak',
            'Tanggal Tindak Lanjut',
            'Sanksi / Tindak Lanjut'
        ];
    }

    public function map($pengaduan): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $pengaduan->kode_tiket,
            $pengaduan->created_at->format('d/m/Y H:i'),
            $pengaduan->nama_pelapor,
            $pengaduan->nomor_hp,
            $pengaduan->kategori_laporan,
            $pengaduan->judul_laporan,
            $pengaduan->isi_laporan,
            \Carbon\Carbon::parse($pengaduan->tanggal_kejadian)->format('d/m/Y'),
            $pengaduan->lokasi_kejadian,
            $pengaduan->tingkat_pelanggaran ?? '-',
            strtoupper($pengaduan->status),
            $pengaduan->investigator->name ?? '-',
            $pengaduan->kesimpulan ?? '-',
            $pengaduan->pihak_penindak ?? '-',
            $pengaduan->tanggal_tindak_lanjut ? \Carbon\Carbon::parse($pengaduan->tanggal_tindak_lanjut)->format('d/m/Y') : '-',
            $pengaduan->tindak_lanjut ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F172A']]],
        ];
    }
}
