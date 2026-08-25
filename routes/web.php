<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\AdminController; 
use Illuminate\Support\Facades\Route;
use App\Models\Pengaduan;

// --- HALAMAN UTAMA ---
Route::get('/', function () {
    $totalLaporan = Pengaduan::count();
    $laporanSelesai = Pengaduan::where('status', 'selesai')->count();
    
    $persentase = 0;
    if ($totalLaporan > 0) {
        $persentase = round(($laporanSelesai / $totalLaporan) * 100);
    }

    $laporanTerbaru = Pengaduan::latest()->take(2)->get();

    return view('welcome', compact('totalLaporan', 'persentase', 'laporanTerbaru'));
});

// --- DASHBOARD UMUM (REDIRECT SESUAI PERAN) ---
Route::get('/dashboard', function () {
    $peran = auth()->user()->peran;
    if ($peran === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($peran === 'investigator') {
        return redirect()->route('investigator.dashboard');
    } else {
        return redirect()->route('pelapor.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// --- FITUR LACAK (PUBLIC) ---
Route::get('/lacak', [PengaduanController::class, 'formLacak'])->name('lacak');
Route::post('/lacak', [PengaduanController::class, 'cariLacak'])->name('lacak.cari');



// ROUTE FORMULIR PENGADUAN (Dikeluarkan dari Grup agar Redirect Login Berhasil)
Route::get('/pelapor/pengaduan/buat', [PengaduanController::class, 'create'])
    ->name('pelapor.create')
    ->middleware('auth');


// === GRUP ROUTE YANG BUTUH LOGIN (AUTH) ===
Route::middleware('auth')->group(function () {
    
    // 1. AREA PELAPOR 
    Route::get('/pelapor/dashboard', [PengaduanController::class, 'index'])->name('pelapor.dashboard');
    Route::post('/pelapor/kirim', [PengaduanController::class, 'store'])->name('pelapor.store');
    Route::post('/pelapor/informasi-tambahan', [PengaduanController::class, 'storeInformasiTambahan'])->name('pelapor.informasi.store');

    // 2. AREA INVESTIGATOR 
    Route::middleware('peran:investigator')->group(function () {
        Route::get('/investigator/dashboard', [PengaduanController::class, 'indexInvestigator'])
            ->name('investigator.dashboard');
        Route::get('/investigator/laporan/{id}', [PengaduanController::class, 'showInvestigator'])
            ->name('investigator.show');    
        Route::put('/investigator/laporan/{id}', [PengaduanController::class, 'updateInvestigator'])
            ->name('investigator.update');
    });

    // 3. AREA ADMINISTRATOR (PUSAT KENDALI TUNGGAL)
    Route::middleware('peran:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])
            ->name('admin.dashboard');
        Route::get('/admin/laporan/{id}', [AdminController::class, 'show'])
            ->name('admin.show');
        Route::put('/admin/kasus/{id}/verifikasi', [AdminController::class, 'verifikasiKasus'])
            ->name('admin.kasus.verifikasi');
        Route::get('/admin/tindak-lanjut/{id}', [AdminController::class, 'editTindakLanjut'])
            ->name('admin.tindaklanjut.edit');
        Route::put('/admin/tindak-lanjut/{id}', [AdminController::class, 'updateTindakLanjut'])
            ->name('admin.tindaklanjut.update');
        Route::put('/admin/kasus/{id}', [App\Http\Controllers\AdminController::class, 'updateKasus'])
            ->name('admin.kasus.update');
        Route::delete('/admin/kasus/{id}', [App\Http\Controllers\AdminController::class, 'destroyKasus'])
            ->name('admin.kasus.destroy');
        Route::put('/admin/pelanggaran/{id}', [App\Http\Controllers\AdminController::class, 'updatePelanggaran'])
            ->name('admin.pelanggaran.update');
        Route::delete('/admin/pelanggaran/{id}', [App\Http\Controllers\AdminController::class, 'destroyPelanggaran'])
            ->name('admin.pelanggaran.destroy');
        Route::put('/admin/investigasi/{id}', [App\Http\Controllers\AdminController::class, 'updateInvestigasi'])
            ->name('admin.investigasi.update');
        Route::delete('/admin/investigasi/{id}', [App\Http\Controllers\AdminController::class, 'destroyInvestigasi'])
            ->name('admin.investigasi.destroy');
        Route::put('/admin/tindaklanjut/{id}', [App\Http\Controllers\AdminController::class, 'updateTindakLanjut'])
            ->name('admin.tindaklanjut.update');
        Route::delete('/admin/tindaklanjut/{id}', [App\Http\Controllers\AdminController::class, 'destroyTindakLanjut'])
            ->name('admin.tindaklanjut.destroy');
        Route::put('/admin/bukti/{id}', [App\Http\Controllers\AdminController::class, 'updateBukti'])
            ->name('admin.bukti.update');
        Route::delete('/admin/bukti/{id}', [App\Http\Controllers\AdminController::class, 'destroyBukti'])
            ->name('admin.bukti.destroy');
        Route::put('/admin/tanggapan/{id}', [App\Http\Controllers\AdminController::class, 'updateTanggapan'])
            ->name('admin.tanggapan.update');
        Route::delete('/admin/tanggapan/{id}', [App\Http\Controllers\AdminController::class, 'destroyTanggapan'])
            ->name('admin.tanggapan.destroy');
        Route::get('admin/kasus/{id}/cetak', [App\Http\Controllers\AdminController::class, 'cetakPdf'])
            ->name('admin.kasus.cetak');
        Route::get('/admin/rekap/{kategori}', [App\Http\Controllers\AdminController::class, 'cetakRekap'])
            ->name('admin.rekap.cetak');
        Route::get('/admin/kasus/export/excel', [App\Http\Controllers\AdminController::class, 'exportExcel'])
            ->name('admin.kasus.export.excel');
        // CRUD MASTER DATA AKSES INTERNAL
        Route::post('/admin/pegawai', [AdminController::class, 'storePegawai'])->name('admin.pegawai.store');
        Route::put('/admin/pegawai/{id}', [AdminController::class, 'updatePegawai'])->name('admin.pegawai.update');
        Route::delete('/admin/pegawai/{id}', [AdminController::class, 'destroyPegawai'])->name('admin.pegawai.destroy');
        // CRUD Master Data Pegawai
        Route::post('/admin/master-pegawai', [AdminController::class, 'storeMasterPegawai'])->name('admin.master_pegawai.store');
        Route::put('/admin/master-pegawai/{id}', [AdminController::class, 'updateMasterPegawai'])->name('admin.master_pegawai.update');
        Route::delete('/admin/master-pegawai/{id}', [AdminController::class, 'destroyMasterPegawai'])->name('admin.master_pegawai.destroy');
        // CRUD MASTER DATA PELAPOR
        Route::post('/admin/pengguna', [AdminController::class, 'storePengguna'])->name('admin.pengguna.store');
        Route::put('/admin/pengguna/{id}', [AdminController::class, 'updatePengguna'])->name('admin.pengguna.update');
        Route::delete('/admin/pengguna/{id}', [AdminController::class, 'destroyPengguna'])->name('admin.pengguna.destroy');
    });

    // 4. Route Profile Bawaan
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

}); // <--- Penutup Grup Auth

require __DIR__.'/auth.php';