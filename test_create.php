<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$k = new App\Models\Pengaduan();
$k->kode_tiket='TEST-123';
$k->judul_laporan='Test';
$k->isi_laporan='Test';
$k->tanggal_kejadian='2026-07-20';
$k->lokasi_kejadian='Test';
$k->kategori_laporan='Korupsi';
$k->status='masuk';
$k->save();
echo $k->id;
