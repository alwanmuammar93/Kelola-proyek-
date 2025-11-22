<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyek;

class ProyekController extends Controller
{
    public function index()
    {
        $proyeks = Proyek::all();
        return view('proyek.index', compact('proyeks'));
    }

    public function create()
    {
        return view('proyek.create');
    }

    public function store(Request $request)
    {
        // 🟢 Validasi data — disamakan dengan ENUM di migration
        $validated = $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'status' => 'required|in:Belum_Dimulai,Sedang Berjalan,Selesai',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'deskripsi' => 'nullable|string'
        ]);

        // Jika status Belum_Dimulai → kosongkan tanggal
        if ($validated['status'] === 'Belum_Dimulai') {
            $validated['tanggal_mulai'] = null;
            $validated['tanggal_selesai'] = null;
        }

        // Simpan proyek baru
        $proyek = Proyek::create($validated);

        // Simpan ID proyek terakhir ke session
        session(['last_proyek_id' => $proyek->id_proyek]); // ✅ disesuaikan ke id_proyek

        return redirect()->route('proyek.index')
                         ->with('success', 'Proyek berhasil ditambahkan. Anda dapat membuat RAB untuk proyek ini.');
    }

    public function edit($id_proyek)
    {
        // ✅ disesuaikan ke id_proyek
        $proyek = Proyek::findOrFail($id_proyek);
        return view('proyek.edit', compact('proyek'));
    }

    public function update(Request $request, $id_proyek)
    {
        // ✅ tetap mempertahankan semua validasi asli
        $validated = $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'status' => 'required|in:Belum_Dimulai,Sedang Berjalan,Selesai',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'deskripsi' => 'nullable|string'
        ]);

        if ($validated['status'] === 'Belum_Dimulai') {
            $validated['tanggal_mulai'] = null;
            $validated['tanggal_selesai'] = null;
        }

        $proyek = Proyek::findOrFail($id_proyek);
        $proyek->update($validated);

        return redirect()->route('proyek.index')->with('success', 'Data proyek berhasil diperbarui.');
    }

    public function destroy($id_proyek)
    {
        // ✅ disesuaikan ke id_proyek
        $proyek = Proyek::findOrFail($id_proyek);
        $proyek->delete();

        return redirect()->route('proyek.index')->with('success', 'Proyek berhasil dihapus.');
    }

    // 🟢 Tambahan baru: tombol KWITANSI akan mengarah ke halaman CRUD kwitansi
    public function kwitansi($id_proyek)
    {
        $proyek = Proyek::findOrFail($id_proyek);
        // arahkan ke view kwitansi dashboard
        return redirect()->route('kwitansi.index', ['id_proyek' => $proyek->id_proyek]);
    }
}
