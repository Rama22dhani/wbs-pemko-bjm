<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
            'deskripsi'     => 'nullable|string',
        ]);

        Kategori::create($request->all());

        return redirect()->back()->with('success', 'Kategori pelanggaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $id,
            'deskripsi'     => 'nullable|string',
        ]);

        $kategori->update($request->all());

        return redirect()->back()->with('success', 'Kategori pelanggaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        
        // Proteksi jika kategori masih dipakai oleh data kasus
        if ($kategori->pengaduans()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan pada data kasus.');
        }

        $kategori->delete();
        return redirect()->back()->with('success', 'Kategori pelanggaran berhasil dihapus.');
    }
}
