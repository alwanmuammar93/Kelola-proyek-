<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    // ==============================
    // TAMPIL SEMUA DATA PENJUALAN
    // ==============================
    public function index()
    {
        $penjualans = Penjualan::all();
        return view('penjualan.index', compact('penjualans'));
    }

    // ==============================
    // FORM TAMBAH DATA BARU
    // ==============================
    public function create()
    {
        return view('penjualan.create');
    }

    // ==============================
    // SIMPAN DATA BARU
    // ==============================
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        $total = $request->jumlah * $request->harga_satuan;

        Penjualan::create([
            'nama_barang' => $request->nama_barang,
            'jumlah' => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
            'total' => $total,
        ]);

        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil ditambahkan!');
    }

    // ==============================
    // EDIT DATA
    // ==============================
    public function edit($id)
    {
        // 🔹 Sesuaikan dengan primary key di model → id_barang
        $penjualan = Penjualan::where('id_barang', $id)->firstOrFail();

        return view('penjualan.edit', compact('penjualan'));
    }

    // ==============================
    // UPDATE DATA
    // ==============================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        // 🔹 Gunakan id_barang agar sesuai dengan model
        $penjualan = Penjualan::where('id_barang', $id)->firstOrFail();

        $penjualan->update([
            'nama_barang' => $request->nama_barang,
            'jumlah' => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
            'total' => $request->jumlah * $request->harga_satuan,
        ]);

        return redirect()->route('penjualan.index')->with('success', 'Data berhasil diperbarui!');
    }

    // ==============================
    // HAPUS DATA
    // ==============================
    public function destroy($id)
    {
        // 🔹 Sesuaikan dengan primary key → id_barang
        $penjualan = Penjualan::where('id_barang', $id)->firstOrFail();

        $penjualan->delete();

        return redirect()->route('penjualan.index')->with('success', 'Data berhasil dihapus!');
    }
}
