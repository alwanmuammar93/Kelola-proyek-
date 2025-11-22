<?php

namespace App\Http\Controllers;

use App\Models\Rab;
use App\Models\Proyek;
use Illuminate\Http\Request;

class RabController extends Controller
{
    /**
     * Dashboard umum RAB
     */
    public function dashboard()
    {
        $rabs = Rab::with('proyek')->orderBy('id_rab', 'desc')->get();
        return view('Rab.dashboard', compact('rabs'));
    }

    /**
     * Halaman Admin -> Pilih Proyek untuk Kelola RAB
     */
    public function adminIndex()
    {
        $proyeks = Proyek::all();
        return view('Rab.select_project', compact('proyeks'));
    }

    /**
     * Halaman pilih proyek
     */
    public function selectProject()
    {
        $proyeks = Proyek::all();
        return view('Rab.select_project', compact('proyeks'));
    }

    /**
     * Menampilkan daftar RAB per proyek
     */
    public function index($id_proyek)
    {
        $proyek = Proyek::findOrFail($id_proyek);
        $rabs   = Rab::where('id_proyek', $id_proyek)->get();

        return view('Rab.index', compact('rabs', 'proyek'));
    }

    public function create($id_proyek)
    {
        $proyek = Proyek::findOrFail($id_proyek);
        return view('Rab.create', compact('proyek'));
    }

    /**
     * ======== PERBAIKAN UTAMA: FUNGSI JSON =========
     */
    public function getRabDetails($id_rab)
    {
        $rab = Rab::find($id_rab);

        if (!$rab) {
            return response()->json([
                'status' => false,
                'message' => 'Data RAB tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'owner' => $rab->owner,
                'nama_pekerjaan' => $rab->nama_pekerjaan,
                'rincian_pekerjaan' => $rab->rincian_pekerjaan,
                'jumlah' => $rab->jumlah,
                'satuan' => $rab->satuan,
                'total' => $rab->total,
            ]
        ]);
    }
    /**
     * ========= END OF FIX ============
     */

    /**
     * Store RAB Baru
     */
    public function store(Request $request, $id_proyek)
    {
        $validated = $request->validate([
            'no_rab' => 'required|string|max:50|unique:rabs,no_rab',
            'perihal' => 'required|string|max:100',
            'owner' => 'required|string|max:100',
            'nama_pekerjaan' => 'required|string|max:150',

            'rincian_pekerjaan' => 'required|array|min:1',
            'rincian_pekerjaan.*' => 'required|string',

            'satuan' => 'required|array|min:1',
            'satuan.*' => 'required|string|max:20',

            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|integer|min:0',

            'biaya_material_rincian' => 'required|array|min:1',
            'biaya_material_rincian.*' => 'required|numeric|min:0',

            'status' => 'required|string',
            'total' => 'nullable|numeric|min:0',
        ]);

        $rincianData = [];
        $count = max(
            count($validated['rincian_pekerjaan']),
            count($validated['satuan']),
            count($validated['jumlah']),
            count($validated['biaya_material_rincian'])
        );

        for ($i = 0; $i < $count; $i++) {
            $rincianData[] = [
                'rincian' => $validated['rincian_pekerjaan'][$i] ?? '',
                'satuan' => $validated['satuan'][$i] ?? '',
                'jumlah' => $validated['jumlah'][$i] ?? 0,
                'biaya_material' => $validated['biaya_material_rincian'][$i] ?? 0,
            ];
        }

        $totalBiayaMaterial = array_sum($validated['biaya_material_rincian']);
        $totalFinal = $validated['total'] ?? $totalBiayaMaterial;

        Rab::create([
            'id_proyek' => $id_proyek,
            'no_rab' => $validated['no_rab'],
            'perihal' => $validated['perihal'],
            'owner' => $validated['owner'],
            'nama_pekerjaan' => $validated['nama_pekerjaan'],
            'rincian_pekerjaan' => $rincianData,
            'jumlah' => array_sum($validated['jumlah']),
            'satuan' => 'Mixed',
            'total' => $totalFinal,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('rab.index', ['id_proyek' => $id_proyek])
            ->with('success', 'RAB berhasil ditambahkan.');
    }

    public function edit($id_rab)
    {
        $rab = Rab::findOrFail($id_rab);
        $proyek = $rab->proyek;

        return view('Rab.edit', compact('rab', 'proyek'));
    }

    public function update(Request $request, $id_rab)
    {
        $rab = Rab::findOrFail($id_rab);

        $validated = $request->validate([
            'no_rab' => 'required|string|max:50|unique:rabs,no_rab,' . $rab->id_rab . ',id_rab',
            'perihal' => 'required|string|max:100',
            'owner' => 'required|string|max:100',
            'nama_pekerjaan' => 'required|string|max:150',

            'rincian_pekerjaan' => 'required|array|min:1',
            'rincian_pekerjaan.*' => 'required|string',

            'satuan' => 'required|array|min:1',
            'satuan.*' => 'required|string|max:20',

            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|integer|min:0',

            'biaya_material_rincian' => 'required|array|min:1',
            'biaya_material_rincian.*' => 'required|numeric|min:0',

            'status' => 'required|string',
            'total' => 'nullable|numeric|min:0',
        ]);

        $rincianData = [];
        $count = max(
            count($validated['rincian_pekerjaan']),
            count($validated['satuan']),
            count($validated['jumlah']),
            count($validated['biaya_material_rincian'])
        );

        for ($i = 0; $i < $count; $i++) {
            $rincianData[] = [
                'rincian' => $validated['rincian_pekerjaan'][$i] ?? '',
                'satuan' => $validated['satuan'][$i] ?? '',
                'jumlah' => $validated['jumlah'][$i] ?? 0,
                'biaya_material' => $validated['biaya_material_rincian'][$i] ?? 0,
            ];
        }

        $totalBiayaMaterial = array_sum($validated['biaya_material_rincian']);
        $totalFinal = $validated['total'] ?? $totalBiayaMaterial;

        $rab->update([
            'no_rab' => $validated['no_rab'],
            'perihal' => $validated['perihal'],
            'owner' => $validated['owner'],
            'nama_pekerjaan' => $validated['nama_pekerjaan'],
            'rincian_pekerjaan' => $rincianData,
            'jumlah' => array_sum($validated['jumlah']),
            'satuan' => 'Mixed',
            'total' => $totalFinal,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('rab.index', ['id_proyek' => $rab->id_proyek])
            ->with('success', 'RAB berhasil diperbarui.');
    }

    public function destroy($id_rab)
    {
        $rab = Rab::findOrFail($id_rab);
        $rab->delete();

        return back()->with('success', 'Data RAB berhasil dihapus.');
    }
}
