# 🏛️ Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai Pemerintah Kota Banjarmasin

**Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai Pemerintah Kota Banjarmasin** adalah sebuah sistem informasi berbasis web _(Whistleblowing System)_ yang dirancang untuk memfasilitasi pelaporan, verifikasi, investigasi, dan penindakan dugaan pelanggaran disiplin maupun etika aparatur di lingkungan Pemerintah Kota Banjarmasin.

Aplikasi ini dikembangkan sebagai luaran dari program **Praktek Kerja Lapangan (PKL)** mahasiswa Program Studi Teknologi Informasi Universitas Islam Kalimantan Muhammad Arsyad Al Banjari (UNISKA) di **Dinas Komunikasi, Informatika, dan Statistik (Diskominfotik) Kota Banjarmasin**.

---

## ✨ Fitur Utama (Role-Based Access Control)

Aplikasi ini mengadopsi alur birokrasi nyata dengan pemisahan hak akses berbasis peran:

### 1. 👤 Pelapor (Masyarakat / ASN)

- **Kirim Pengaduan:** Melaporkan indikasi pelanggaran (Pungli, Gratifikasi, Penyalahgunaan Aset, Disiplin, Nepotisme) disertai bukti awal.
- **Lacak Tiket:** Memantau perkembangan status laporan secara transparan (Masuk ➔ Investigasi ➔ Selesai / Ditolak).
- **Informasi Susulan:** Mengunggah bukti atau memberikan keterangan tambahan pada kasus yang sedang berjalan.

### 2. 🛡️ Administrator (Diskominfotik)

- **Verifikasi & Screening:** Memeriksa keabsahan laporan masuk serta menolak laporan anomali/tidak relevan disertai alasan resmi.
- **Disposisi Kasus:** Menentukan tingkat pelanggaran (Ringan/Sedang/Berat) dan menugaskan investigator yang berwenang.
- **Tindak Lanjut & Sanksi:** Menginput keputusan penjatuhan sanksi resmi dari Pejabat Pembina Kepegawaian (PPK).
- **Master Data Pegawai:** Pengelolaan basis data aparatur dan penugasan hak akses akun.
- **Cetak Dokumen & Rekap:** Menghasilkan dokumen rekapitulasi data dan berkas laporan kasus dalam format PDF.

### 3. 🕵️ Investigator (Inspektorat Daerah / BKPSDM)

- **Manajemen Kasus Terdisposisi:** Mengakses dan menangani daftar kasus yang didelegasikan secara spesifik.
- **Kertas Kerja Investigasi:** Mencatat fakta temuan lapangan, klarifikasi pihak terkait/saksi, dan rekomendasi sanksi.
- **Unggah Dokumen Temuan:** Mengunggah Berita Acara Pemeriksaan (BAP) dan berkas pendukung hasil audit lapangan.

---

## 🛠️ Teknologi yang Digunakan

- **Framework:** [Laravel 10](https://laravel.com/) (PHP)
- **Frontend:** [Tailwind CSS](https://tailwindcss.com/) & Alpine.js
- **Database:** MySQL
- **PDF Generator:** DomPDF

---

## 🚀 Cara Menjalankan Proyek (Lokal)

1. Install dependensi PHP & Node.js:
   composer install
   npm install
2. Konfigurasi Environment:
   cp .env.example .env
   php artisan key:generate
3. Migrasi Database & Seeder:
   php artisan migrate --seed
4. Jalankan Server Lokal:
   php artisan serve
   npm run dev
   Aplikasi siap diakses melalui peramban pada tautan http://127.0.0.1:8000.
