<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Instansi;

class InstansiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:255|unique:instansis',
            'singkatan' => 'nullable|string|max:50',
        ]);

        Instansi::create($request->all());

        return redirect()->back()->with('success', 'Data Instansi berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $instansi = Instansi::findOrFail($id);

        $request->validate([
            'nama_instansi' => 'required|string|max:255|unique:instansis,nama_instansi,' . $instansi->id,
            'singkatan' => 'nullable|string|max:50',
        ]);

        $instansi->update($request->all());

        return redirect()->back()->with('success', 'Data Instansi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $instansi = Instansi::findOrFail($id);
        $instansi->delete();

        return redirect()->back()->with('success', 'Data Instansi berhasil dihapus!');
    }
}
