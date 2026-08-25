<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$request = new \Illuminate\Http\Request([
    'keputusan' => 'terima',
    'tingkat_pelanggaran' => 'Berat',
    'investigator_id' => 3, // Pahmi
    'catatan_verifikator' => 'Test'
]);

$controller = new \App\Http\Controllers\AdminController();
$controller->verifikasiKasus($request, 3);

$k = \App\Models\Pengaduan::find(3);
echo json_encode(['status' => $k->status, 'investigator' => $k->investigator_id]);
