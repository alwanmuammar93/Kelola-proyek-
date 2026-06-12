<?php

namespace App\Http\Controllers;

use App\Models\Rab;
use App\Models\Proyek;
use App\Models\Notification;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RabController extends Controller
{
    /**
     * Dashboard utama RAB - Menampilkan SEMUA RAB dari SEMUA proyek
     */
    public function index(Request $request)
    {
        $query = Rab::with('proyek');

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_rab', 'LIKE', "%{$search}%")
                  ->orWhere('owner', 'LIKE', "%{$search}%")
                  ->orWhere('nama_pekerjaan', 'LIKE', "%{$search}%")
                  ->orWhereHas('proyek', function($q2) use ($search) {
                      $q2->where('nama_proyek', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'terbaru');
        
        switch ($sortBy) {
            case 'terlama':
                $query->orderBy('created_at', 'asc');
                break;
            case 'no_rab_asc':
                $query->orderBy('no_rab', 'asc');
                break;
            case 'no_rab_desc':
                $query->orderBy('no_rab', 'desc');
                break;
            case 'total_tertinggi':
                $query->orderBy('total', 'desc');
                break;
            case 'total_terendah':
                $query->orderBy('total', 'asc');
                break;
            case 'status':
                $query->orderBy('status', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $rabs = $query->get();

        return view('Rab.index', compact('rabs'));
    }

    /**
     * Form Tambah RAB Baru
     */
    public function create()
    {
        $proyeks = Proyek::orderBy('nama_proyek', 'asc')->get();
        return view('Rab.create', compact('proyeks'));
    }

    /**
     * 🔥 NEW: Generate No RAB Otomatis (AJAX)
     * Format: 002/SLA/RAB/I/2025
     */
    public function generateNoRab(Request $request)
    {
        $request->validate([
            'id_proyek' => 'required|exists:proyeks,id_proyek'
        ]);

        try {
            $noRab = $this->autoGenerateNoRab();
            
            return response()->json([
                'success' => true,
                'no_rab' => $noRab
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate No RAB: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔥 NEW: Function untuk Auto Generate No RAB
     * Format: 002/SLA/RAB/I/2025
     */
    private function autoGenerateNoRab()
    {
        $now = Carbon::now();
        $tahun = $now->year;
        $bulan = $now->month;
        
        // Konversi bulan ke angka Romawi
        $bulanRomawi = $this->convertToRoman($bulan);
        
        // Hitung nomor urut berdasarkan bulan dan tahun saat ini
        $lastRab = Rab::whereYear('created_at', $tahun)
                      ->whereMonth('created_at', $bulan)
                      ->orderBy('created_at', 'desc')
                      ->first();
        
        if ($lastRab && preg_match('/^(\d+)\//', $lastRab->no_rab, $matches)) {
            $nomorUrut = intval($matches[1]) + 1;
        } else {
            $nomorUrut = 1;
        }
        
        // Format: 002/SLA/RAB/I/2025
        $noRab = sprintf('%03d/SLA/RAB/%s/%d', $nomorUrut, $bulanRomawi, $tahun);
        
        // Double check: pastikan no_rab belum ada di database
        while (Rab::where('no_rab', $noRab)->exists()) {
            $nomorUrut++;
            $noRab = sprintf('%03d/SLA/RAB/%s/%d', $nomorUrut, $bulanRomawi, $tahun);
        }
        
        return $noRab;
    }

    /**
     * 🔥 NEW: Konversi angka bulan ke Romawi
     */
    private function convertToRoman($number)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        
        return $map[$number] ?? 'I';
    }

    /**
     * Get RAB Details (AJAX)
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
                'no_rab' => $rab->no_rab,
                'perihal' => $rab->perihal,
                'owner' => $rab->owner,
                'nama_pekerjaan' => $rab->nama_pekerjaan,
                'rincian_pekerjaan' => $rab->rincian_pekerjaan,
                'jumlah' => $rab->jumlah,
                'satuan' => $rab->satuan,
                'total' => $rab->total,
                'status' => $rab->status,
            ]
        ]);
    }

    /**
     * Store RAB Baru
     * 🔥 WITH AUTO GENERATE NO RAB & NOTIFICATION
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_proyek' => 'required|exists:proyeks,id_proyek',
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

        // Siapkan data rincian dengan subtotal
        $rincianData = [];
        $totalRAB = 0;
        
        $count = max(
            count($validated['rincian_pekerjaan']),
            count($validated['satuan']),
            count($validated['jumlah']),
            count($validated['biaya_material_rincian'])
        );

        for ($i = 0; $i < $count; $i++) {
            $jumlah = floatval($validated['jumlah'][$i] ?? 0);
            $biayaMaterial = floatval($validated['biaya_material_rincian'][$i] ?? 0);
            $subtotal = $jumlah * $biayaMaterial;
            
            $rincianData[] = [
                'rincian' => $validated['rincian_pekerjaan'][$i] ?? '',
                'satuan' => $validated['satuan'][$i] ?? '',
                'jumlah' => $jumlah,
                'biaya_material' => $biayaMaterial,
                'subtotal' => $subtotal,
            ];
            
            $totalRAB += $subtotal;
        }

        // Simpan RAB
        $rab = Rab::create([
            'id_proyek' => $validated['id_proyek'],
            'no_rab' => $validated['no_rab'],
            'perihal' => $validated['perihal'],
            'owner' => $validated['owner'],
            'nama_pekerjaan' => $validated['nama_pekerjaan'],
            'rincian_pekerjaan' => $rincianData,
            'jumlah' => array_sum($validated['jumlah']),
            'satuan' => 'Mixed',
            'total' => $totalRAB,
            'status' => $validated['status'],
        ]);

        // Update status proyek
        $proyek = Proyek::find($validated['id_proyek']);
        if ($proyek) {
            $proyek->status = 'RAB Telah Dibuat';
            $proyek->save();
        }

        // 🔥 BUAT NOTIFIKASI OTOMATIS
        Notification::createRabNotification('created', $rab);
        
        // 🔥 Notifikasi tambahan berdasarkan status
        if ($validated['status'] == 'Belum Disetujui') {
            Notification::createRabNotification('belum_disetujui', $rab);
        }

        return redirect()
            ->route('rab.index')
            ->with('success', 'RAB berhasil ditambahkan dan notifikasi telah dibuat!');
    }

    /**
     * Form Edit RAB
     */
    public function edit($id_rab)
    {
        $rab = Rab::findOrFail($id_rab);
        $proyek = $rab->proyek;
        $proyeks = Proyek::orderBy('nama_proyek', 'asc')->get();

        return view('Rab.edit', compact('rab', 'proyek', 'proyeks'));
    }

    /**
     * Update RAB
     * 🔥 WITH NOTIFICATION SYSTEM
     */
    public function update(Request $request, $id_rab)
    {
        $rab = Rab::findOrFail($id_rab);
        
        // Simpan status lama untuk deteksi perubahan
        $oldStatus = $rab->status;

        $validated = $request->validate([
            'id_proyek' => 'required|exists:proyeks,id_proyek',
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

        // Siapkan data rincian
        $rincianData = [];
        $totalRAB = 0;
        
        $count = max(
            count($validated['rincian_pekerjaan']),
            count($validated['satuan']),
            count($validated['jumlah']),
            count($validated['biaya_material_rincian'])
        );

        for ($i = 0; $i < $count; $i++) {
            $jumlah = floatval($validated['jumlah'][$i] ?? 0);
            $biayaMaterial = floatval($validated['biaya_material_rincian'][$i] ?? 0);
            $subtotal = $jumlah * $biayaMaterial;
            
            $rincianData[] = [
                'rincian' => $validated['rincian_pekerjaan'][$i] ?? '',
                'satuan' => $validated['satuan'][$i] ?? '',
                'jumlah' => $jumlah,
                'biaya_material' => $biayaMaterial,
                'subtotal' => $subtotal,
            ];
            
            $totalRAB += $subtotal;
        }

        // Update RAB
        $rab->update([
            'id_proyek' => $validated['id_proyek'],
            'no_rab' => $validated['no_rab'],
            'perihal' => $validated['perihal'],
            'owner' => $validated['owner'],
            'nama_pekerjaan' => $validated['nama_pekerjaan'],
            'rincian_pekerjaan' => $rincianData,
            'jumlah' => array_sum($validated['jumlah']),
            'satuan' => 'Mixed',
            'total' => $totalRAB,
            'status' => $validated['status'],
        ]);

        // Update proyek jika ganti
        if ($rab->id_proyek != $validated['id_proyek']) {
            $proyekBaru = Proyek::find($validated['id_proyek']);
            if ($proyekBaru) {
                $proyekBaru->status = 'RAB Telah Dibuat';
                $proyekBaru->save();
            }
        }

        // 🔥 BUAT NOTIFIKASI JIKA STATUS BERUBAH
        if ($oldStatus != $validated['status']) {
            $notifType = match($validated['status']) {
                'Belum Disetujui' => 'belum_disetujui',
                'Disetujui' => 'disetujui',
                'Berjalan' => 'berjalan',
                'Selesai' => 'selesai',
                default => 'updated'
            };
            
            Notification::createRabNotification($notifType, $rab->fresh());
        } else {
            // Jika tidak ada perubahan status, buat notif update biasa
            Notification::createRabNotification('updated', $rab->fresh());
        }

        return redirect()
            ->route('rab.index')
            ->with('success', 'RAB berhasil diperbarui dan notifikasi telah dibuat.');
    }

    /**
     * Hapus RAB
     * 🔥 WITH NOTIFICATION SYSTEM
     */
    public function destroy($id_rab)
    {
        $rab = Rab::findOrFail($id_rab);
        $id_proyek = $rab->id_proyek;
        
        // 🔥 Buat notifikasi sebelum dihapus (simpan data RAB dulu)
        $rabData = clone $rab;
        
        $rab->delete();

        // Update status proyek jika tidak ada RAB lagi
        $jumlahRabProyek = Rab::where('id_proyek', $id_proyek)->count();
        
        if ($jumlahRabProyek == 0) {
            $proyek = Proyek::find($id_proyek);
            if ($proyek) {
                $proyek->status = 'RAB Belum Dibuat';
                $proyek->save();
            }
        }
        
        // 🔥 Buat notifikasi penghapusan
        Notification::createRabNotification('deleted', $rabData);

        return redirect()
            ->route('rab.index')
            ->with('success', 'Data RAB berhasil dihapus dan notifikasi telah dibuat.');
    }

    /**
     * Download RAB sebagai PDF
     */
    public function downloadPDF($id_rab)
    {
        $rab = Rab::with('proyek')->findOrFail($id_rab);
        
        $data = [
            'rab' => $rab,
            'rincian' => $rab->rincian_pekerjaan,
            'total' => $rab->total,
            'tanggal' => Carbon::now()->locale('id')->translatedFormat('d F Y'),
        ];
        
        $pdf = Pdf::loadView('Rab.pdf-template', $data)
                  ->setPaper('a4', 'portrait');
        
        $filename = 'RAB_' . str_replace('/', '_', $rab->no_rab) . '.pdf';
        
        return $pdf->download($filename);
    }
}