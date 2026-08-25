-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 24, 2026 at 07:26 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sapa_pemkobjm`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('pelapor@gamil.com|127.0.0.1', 'i:1;', 1784821204),
('pelapor@gamil.com|127.0.0.1:timer', 'i:1784821204;', 1784821204),
('pelapor@gemail.com|127.0.0.1', 'i:1;', 1784821039),
('pelapor@gemail.com|127.0.0.1:timer', 'i:1784821039;', 1784821039),
('pelapor@gemil.com|127.0.0.1', 'i:1;', 1784821221),
('pelapor@gemil.com|127.0.0.1:timer', 'i:1784821221;', 1784821221);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_10_165347_create_pengaduans_table', 1),
(5, '2026_02_10_165356_add_role_to_users_table', 1),
(6, '2026_02_12_162013_add_nip_to_pengaduans_table', 1),
(7, '2026_02_13_132804_add_investigator_id_to_pengaduans_table', 1),
(8, '2026_02_13_141234_add_hasil_investigasi_to_pengaduans', 1),
(9, '2026_02_15_075811_add_tingkat_pelanggaran_to_pengaduans', 1),
(10, '2026_02_18_123211_add_catatan_verifikator_to_pengaduans', 1),
(11, '2026_02_20_115827_add_kertas_kerja_to_pengaduans', 1),
(12, '2026_02_20_121226_add_bukti_investigasi_to_pengaduans', 1),
(13, '2026_03_16_130953_add_tindak_lanjut_to_pengaduans', 1),
(14, '2026_04_11_131843_create_tanggapans_table', 1),
(15, '2026_04_19_154912_add_detail_tindak_lanjut_to_pengaduans_table', 1),
(16, '2026_04_27_063810_add_kategori_dan_lampiran_to_tanggapans_table', 1),
(17, '2026_07_18_072948_revisi_tabel_pengaduan', 1),
(18, '2026_07_19_075635_create_pegawais_table', 1),
(19, '2026_07_20_154200_add_alasan_penolakan_to_pengaduans_table', 1),
(20, '2026_07_22_074454_add_jenis_kelamin_to_pegawais_table', 2),
(21, '2026_07_22_075830_add_ttl_and_alamat_to_pegawais_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pegawais`
--

CREATE TABLE `pegawais` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `nip` varchar(255) NOT NULL,
  `nama_pegawai` varchar(255) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text,
  `status_kepegawaian` enum('PNS','PPPK','CPNS','Honorer') NOT NULL,
  `asal_instansi` varchar(255) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `nomor_hp` varchar(255) DEFAULT NULL,
  `status_aktif` enum('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pegawais`
--

INSERT INTO `pegawais` (`id`, `user_id`, `nip`, `nama_pegawai`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `status_kepegawaian`, `asal_instansi`, `jabatan`, `nomor_hp`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 1, '199403102019031005', 'Ahmad Ramadhani S.kom', 'Laki-laki', 'Banjarmasin', '2004-10-22', 'Jl. Belitung Darat GG. Hikmah', 'PNS', 'Inspektorat Daerah Kota Banjarmasin', 'Pegawai', '081251373422', 'Aktif', '2026-07-21 23:30:03', '2026-07-24 02:47:44'),
(2, 2, '198111222005011002', 'Muhammad Hambali S.Kom, M.H', 'Laki-laki', 'Barabai', '2003-11-22', 'Handil Bakti', 'PNS', 'Inspektorat Daerah Kota Banjarmasin', 'PPUPD', '081234567890', 'Aktif', '2026-07-21 23:37:24', '2026-07-24 02:46:56'),
(3, 3, '198605142010011003', 'Muhammad Pahmi S.AP', 'Laki-laki', 'Banjarmasin', '2003-10-08', 'Jl. Melati Indah', 'PNS', 'Inspektorat Daerah Kota Banjarmasin', 'Analis Pelanggaran', '089522774422', 'Aktif', '2026-07-21 23:41:12', '2026-07-24 02:48:06');

-- --------------------------------------------------------

--
-- Table structure for table `pengaduans`
--

CREATE TABLE `pengaduans` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_tiket` varchar(255) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `nama_pelapor` varchar(255) DEFAULT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `nomor_hp` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `judul_laporan` varchar(255) NOT NULL,
  `isi_laporan` text NOT NULL,
  `tanggal_kejadian` date NOT NULL,
  `lokasi_kejadian` varchar(255) NOT NULL,
  `kategori_laporan` varchar(255) NOT NULL,
  `tingkat_pelanggaran` enum('Ringan','Sedang','Berat') DEFAULT NULL,
  `catatan_verifikator` text,
  `alasan_penolakan` text,
  `lampiran_bukti` varchar(255) DEFAULT NULL,
  `status` enum('masuk','verifikasi','investigasi','selesai','ditolak') NOT NULL DEFAULT 'masuk',
  `pesan_susulan` text,
  `lampiran_susulan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `investigator_id` bigint UNSIGNED DEFAULT NULL,
  `hasil_investigasi` text,
  `fakta_lapangan` text,
  `pihak_terlibat` text,
  `kesimpulan` text,
  `tindak_lanjut` text,
  `pihak_penindak` varchar(255) DEFAULT NULL,
  `tanggal_tindak_lanjut` date DEFAULT NULL,
  `bukti_investigasi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengaduans`
--

INSERT INTO `pengaduans` (`id`, `kode_tiket`, `user_id`, `nama_pelapor`, `nip`, `nomor_hp`, `email`, `judul_laporan`, `isi_laporan`, `tanggal_kejadian`, `lokasi_kejadian`, `kategori_laporan`, `tingkat_pelanggaran`, `catatan_verifikator`, `alasan_penolakan`, `lampiran_bukti`, `status`, `pesan_susulan`, `lampiran_susulan`, `created_at`, `updated_at`, `investigator_id`, `hasil_investigasi`, `fakta_lapangan`, `pihak_terlibat`, `kesimpulan`, `tindak_lanjut`, `pihak_penindak`, `tanggal_tindak_lanjut`, `bukti_investigasi`) VALUES
(8, 'KASUS-ZUDXF', 5, 'Muhammad Hadid', NULL, '085298765432', 'hadid@gmail.com', 'Rekrutmen Tim Tenaga Kontrak Tanpa Seleksi Terbuka', 'Oknum pejabat berinisial \"YS\" secara sepihak memasukkan keponakan kandungnya sendiri ke dalam daftar Surat Keputusan (SK) Tenaga Kontrak Pendukung Lapangan tanpa melalui mekanisme rekrutmen atau tes kualifikasi yang standar. Keponakan tersebut diketahui tidak memiliki latar belakang pendidikan yang sesuai dengan posisi yang dijabat. Hal ini menimbulkan konflik kepentingan dan kecemburuan di internal dinas.', '2026-07-14', 'Dinas Kesehatan Kota Banjarmasin', 'Benturan Kepentingan', 'Berat', 'Tindakan memasukkan anggota keluarga/kerabat tanpa proses seleksi resmi merupakan bentuk penyalahgunaan wewenang struktural.', NULL, 'bukti_pengaduan/9WwGGFJxVKqDSnRNPkTFzgh1K0VJWzgvzA280Jd3.jpg', 'selesai', NULL, NULL, '2026-07-24 02:02:14', '2026-08-03 01:17:00', 2, NULL, 'Audit berkas administrasi rekrutmen di Dinas Kesehatan menemukan bahwa nama keponakan terlapor (YS) langsung dimasukkan ke dalam daftar Surat Keputusan (SK) Tenaga Kontrak tanpa ada rekam jejak berkas lamaran, tes wawancara, maupun verifikasi kompetensi teknis.', 'Kasubbag Kepegawaian Dinkes, dan Sdr. YS (Terlapor)', 'TERBUKTI MELANGGAR\r\nPenjatuhan Sanksi Disiplin Tingkat Berat serta pembatalan sepihak SK pengangkatan tenaga kontrak yang bersangkutan.', 'Terlapor (YS), Resmi diberikan sanksi  Disiplin Tingkat Berat dan pembatalan sepihak SK pengangkatan tenaga kontrak', 'Inspektorat Daerah Kota Banjarmasin', '2026-07-24', NULL),
(11, 'KASUS-BXC6J', 5, 'Muhammad Hadid', '19790815 200501 1 008', '085298765432', 'hadid@gmail.com', 'Meninggalkan Meja Layanan dan Nongkrong Saat Jam Kerja', 'Oknum ASN berinisial \"DW\" yang bertugas di loket pelayanan masyarakat tercatat rutin meninggalkan meja kerja antara pukul 10.00 hingga 14.00 WITA hanya untuk nongkrong di warung kopi sekitar kantor. Akibat dari perilaku mangkir saat jam kerja ini, antrean kepengurusan dokumen warga menjadi membludak dan proses pelayanan publik menjadi sangat terlambat.', '2026-07-31', 'Dinas Kependudukan dan Pencatatan Sipil (Disdukcapil) Kota Banjarmasin', 'Pelanggaran Disiplin', 'Sedang', 'Perilaku mangkir saat jam kerja yang berdampak langsung pada penumpukan antrean warga dan penurunan kualitas pelayanan publik', NULL, 'bukti_laporan/Enkb2Q10PI9ceMyboROb0vyhCrFxjVPiwgcNxreC.png', 'selesai', 'Melengkapi pengaduan sebelumnya, saya lampirkan bukti foto kondisi Loket 3 Pelayanan Disdukcapil yang kosong tanpa petugas pada pukul 11.15 WITA beserta foto antrean warga yang menumpuk. Selain itu, saya sertakan juga foto terlapor (Sdr. DW) yang sedang berada di warung kopi di bagian belakang kantor pada jam pelayanan yang sama, lengkap dengan keterangan waktu (timestamp GPS) pada gambar sebagai bukti pendukung.', '1785748378_susulan_Arsip Laporan Ditolak - WBS.pdf', '2026-08-03 00:41:32', '2026-08-03 02:29:03', 3, NULL, 'Hasil inspeksi mendadak dan pemeriksaan rekaman CCTV di loket Disdukcapil membenarkan Sdr. DW secara berulang meninggalkan meja pelayanan masyarakat selama 3 hingga 4 jam pada jam kerja aktif tanpa keterangan sah.', 'Kepala Bidang Pelayanan Disdukcapil dan Sdr. DW (Terlapor)', 'TERBUKTI MELANGGAR\r\nRekomendasi: Penjatuhan Sanksi Disiplin Tingkat Sedang dan pemotongan Tunjangan Penghasilan Pegawai (TPP).', 'Sanksi Disiplin Tingkat Sedang berupa Surat Teguran Tertulis dan Pemotongan TPP sebesar 25% selama 3 bulan.', 'BKPSDM dan Disdukcapil Kota Banjarmasin', '2026-08-03', NULL),
(12, 'KASUS-FC1VH', 4, 'Fauzi Pelapor', NULL, '082211223344', 'pelapor@gmail.com', 'Dugaan Pungli Lolos Uji KIR Kendaraan Angkutan', 'Oknum petugas penguji berinisial \"TM\" meminta sejumlah uang sebesar Rp 300.000 (di luar tarif retribusi resmi) agar mobil pick-up milik saya yang ban-nya sudah mulai aus bisa tetap diloloskan uji KIR. Oknum tersebut mengancam akan mempersulit dokumen jika saya tidak membayar uang pelicin tersebut.', '2026-08-03', 'UPT Pengujian Kendaraan Bermotor (KIR) Dishub', 'Pungli', 'Berat', 'Segera Investigasi', NULL, 'bukti_laporan/len4HkNn2h1ZXfMyOJw9591znNgog6GM9DryXEtz.png', 'selesai', NULL, NULL, '2026-08-03 10:43:54', '2026-08-04 01:09:54', 2, NULL, 'Analisis bukti berupa foto pelapor memiliki kecocokan identik dengan Sdr. TM. Ditemukan juga praktik penyelipan uang di laci meja terlapor dari beberapa sopir angkutan lainnya saat sidak lapangan.', 'Kepala UPT KIR dan Sdr. TM (Terlapor)', 'TERBUKTI MELANGGAR\r\nTerlapor terbukti melakukan Pungli yang membahayakan keselamatan lalu lintas. Direkomendasikan Sanksi Disiplin Tingkat Berat.', 'Pejabat Pembina Kepegawaian menjatuhkan Sanksi Disiplin Tingkat Berat berupa Penurunan Jabatan setingkat lebih rendah selama 12 bulan kepada Sdr. TM. Terlapor langsung ditarik dari pos pengujian kendaraan dan dipindahtugaskan ke bagian staf administrasi internal.', 'Inspektorat Daerah dan Dishub Kota Banjarmasin', '2026-08-04', NULL),
(13, 'KASUS-HI7AW', 4, 'Fauzi Pelapor', NULL, '082211223344', 'pelapor@gmail.com', 'Penerimaan Gratifikasi Barang Mewah dari Kontraktor Proyek', 'Seorang Pejabat Pelaksana Teknis Kegiatan (PPTK) berinisial \"LN\" diketahui menerima bingkisan tas belanja bermerek berisi logam mulia dan amplop tebal dari perwakilan CV rekanan sesaat setelah pencairan termin proyek perbaikan jalan lingkungan disetujui.', '2026-08-03', 'Dinas Perumahan Rakyat dan Kawasan Permukiman', 'Gratifikasi', 'Berat', 'Segera Investigasi', NULL, NULL, 'selesai', 'Sebagai penguat laporan, saya melampirkan foto diam-diam yang memperlihatkan tas belanja eksklusif dari toko perhiasan ternama tergeletak di bawah meja kerja oknum \"LN\", beserta kartu ucapan terima kasih berlogo perusahaan kontraktor pemenang tender tersebut.', '1785782851_susulan_Halaman Lacak Kasus.png', '2026-08-03 10:46:54', '2026-08-04 01:10:52', 2, NULL, 'Penggeledahan laci dan ruang kerja Sdr. LN menemukan barang bukti berupa perhiasan emas seberat 10 gram senilai Rp 14.000.000 yang belum dilaporkan ke Unit Pengendalian Gratifikasi (UPG) melebihi batas waktu maksimal 30 hari.', 'Petugas keamanan dinas dan Sdr. LN (Terlapor)', 'TERBUKTI MELANGGAR\r\nTerlapor terbukti menerima gratifikasi yang berhubungan dengan jabatannya. Direkomendasikan Sanksi Disiplin Tingkat Berat dan pencopotan wewenang sebagai PPTK.', 'Sanksi Disiplin Tingkat Berat berupa Pembebasan dari Jabatannya (Pencopotan Jabatan) diberikan kepada Sdr. LN sesuai aturan disiplin PNS. Seluruh barang bukti gratifikasi telah disita secara resmi oleh Unit Pengendalian Gratifikasi (UPG) Kota Banjarmasin untuk diserahkan kepada negara.', 'BKPSDM dan Inspektorat Daerah Kota Banjarmasin', '2026-08-04', 'bukti_investigasi/cg3NqRO83yPyfcnfOJuLsG0xWFRUTPlBivRqTmeS.png');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2BXFLxjXeAWJ8HDVDlEghWAphiSKHCHsDd3GI6C5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieTE4eTJNMzVoQTNPTEtkUTFNc0VSUmphNVFEUkphNnp2S0xrODVTWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1787040925),
('idjL8XH0QI4pA3bI5NcDfIu1rWz9xLTPKSzKd5Qn', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNnp6MnRGYjN4ZkpWVk9VWWtqSmZoaEVPdVpHeVZXMnpobXZkSElETiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1786979662);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `peran` enum('admin','investigator','pelapor') NOT NULL DEFAULT 'pelapor',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `peran`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Rama Administrator IT', 'admin@sapa.banjarmasinkota.go.id', 'admin', NULL, '$2y$12$XMkBiQMUPnH/iq8lu411AuV1.jM/ogpLVTMsmIoz5WAxPZGs45HY6', NULL, '2026-07-21 23:22:17', '2026-07-21 23:22:17'),
(2, 'Hambali Investigator', 'hambali@sapa.banjarmasinkota.go.id', 'investigator', NULL, '$2y$12$/NKTzZFh2jX0Sfet992DWeHi1QKDqWUzBo1flRWGLdRxIXl6gCMzS', NULL, '2026-07-21 23:22:18', '2026-07-21 23:22:18'),
(3, 'Pahmi Investigator', 'pahmi@sapa.banjarmasinkota.go.id', 'investigator', NULL, '$2y$12$liiwffvLl0xAchWlcVKNJeUrZxYzyWXYI1FRC4V7x3vfd9l0H68uy', NULL, '2026-07-21 23:22:18', '2026-07-21 23:22:18'),
(4, 'Fauzi Pelapor', 'pelapor@gmail.com', 'pelapor', NULL, '$2y$12$KpXSLazB28mBEOAWsBOG2e3XXm/Lx5LrXtVaqjQcirna0KxF9lYU2', NULL, '2026-07-21 23:22:18', '2026-08-03 10:39:50'),
(5, 'Muhammad Hadid', 'hadid@gmail.com', 'pelapor', NULL, '$2y$12$S.Lqr7vRViJqRlPFhuIFsOMtA14856zVvFlTmrMbGpOzRLBFe7HC2', NULL, '2026-07-24 00:35:27', '2026-07-24 00:35:27'),
(6, 'Jess', 'jess@gmail.com', 'pelapor', NULL, '$2y$12$GyYvWaja9yq/le8bZ01SvOQCBI.h9ExynS0a9vDOwZKz/A6dXQbv.', NULL, '2026-07-24 01:21:45', '2026-07-24 01:21:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pegawais`
--
ALTER TABLE `pegawais`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pegawais_nip_unique` (`nip`),
  ADD KEY `pegawais_user_id_foreign` (`user_id`);

--
-- Indexes for table `pengaduans`
--
ALTER TABLE `pengaduans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengaduans_kode_tiket_unique` (`kode_tiket`),
  ADD KEY `pengaduans_user_id_foreign` (`user_id`),
  ADD KEY `pengaduans_investigator_id_foreign` (`investigator_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pegawais`
--
ALTER TABLE `pegawais`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pengaduans`
--
ALTER TABLE `pengaduans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pegawais`
--
ALTER TABLE `pegawais`
  ADD CONSTRAINT `pegawais_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pengaduans`
--
ALTER TABLE `pengaduans`
  ADD CONSTRAINT `pengaduans_investigator_id_foreign` FOREIGN KEY (`investigator_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `pengaduans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
