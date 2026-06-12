<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Notification; // 🔥 IMPORT MODEL NOTIFICATION
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenjualanController extends Controller
{
    /**
     * Tampil semua data penjualan dengan relasi details
     * ✅ UPDATED: Tambah sorting berdasarkan parameter
     */
    public function index(Request $request)
    {
        // Ambil parameter sort_by dari request, default 'total_tertinggi'
        $sortBy = $request->get('sort_by', 'total_tertinggi');
        
        // Query dasar dengan eager loading untuk menghindari N+1 query problem
        $query = Penjualan::with('details');
        
        // Terapkan sorting berdasarkan parameter
        switch ($sortBy) {
            case 'total_terendah':
                $query->orderBy('total', 'asc');
                break;
            
            case 'total_tertinggi':
            default:
                $query->orderBy('total', 'desc');
                break;
        }
        
        // Ambil semua data
        $penjualans = $query->get();
        
        return view('penjualan.index', compact('penjualans'));
    }

    /**
     * Form tambah data baru
     */
    public function create()
    {
        return view('penjualan.create');
    }

    /**
     * ✅ FIXED: Simpan 1 PENJUALAN dengan MULTIPLE DETAILS
     * 🔥 WITH NOTIFICATION SYSTEM
     */
    public function store(Request $request)
    {
        // 🔍 DEBUG 1: Log semua data yang masuk
        Log::info('=== DATA YANG MASUK ===');
        Log::info($request->all());

        // ✅ Validasi input
        $validated = $request->validate([
            'nama_sales' => 'required|string|max:255',
            'rincian' => 'required|array|min:1',
            'rincian.*' => 'required|string|max:255',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|integer|min:1',
            'harga_satuan' => 'required|array|min:1',
            'harga_satuan.*' => 'required|numeric|min:0',
            'status' => 'nullable|string|in:pending,paid,shipped,completed,cancelled', // 🔥 TAMBAHAN: field status
        ], [
            // Nama Sales
            'nama_sales.required' => 'Nama sales wajib diisi',
            'nama_sales.string' => 'Nama sales harus berupa teks',
            'nama_sales.max' => 'Nama sales maksimal 255 karakter',
            
            // Rincian
            'rincian.required' => 'Rincian wajib diisi',
            'rincian.array' => 'Format rincian tidak valid',
            'rincian.*.required' => 'Setiap rincian wajib diisi',
            'rincian.*.string' => 'Rincian harus berupa teks',
            'rincian.*.max' => 'Rincian maksimal 255 karakter',
            
            // Jumlah
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.array' => 'Format jumlah tidak valid',
            'jumlah.*.required' => 'Setiap jumlah wajib diisi',
            'jumlah.*.integer' => 'Jumlah harus berupa angka',
            'jumlah.*.min' => 'Jumlah minimal 1',
            
            // Harga Satuan
            'harga_satuan.required' => 'Harga satuan wajib diisi',
            'harga_satuan.array' => 'Format harga satuan tidak valid',
            'harga_satuan.*.required' => 'Setiap harga satuan wajib diisi',
            'harga_satuan.*.numeric' => 'Harga satuan harus berupa angka',
            'harga_satuan.*.min' => 'Harga satuan minimal 0',
            
            // Status
            'status.in' => 'Status tidak valid',
        ]);

        // 🔍 DEBUG 2: Log data setelah validasi
        Log::info('=== DATA SETELAH VALIDASI ===');
        Log::info($validated);

        try {
            DB::beginTransaction();

            // ✅ STEP 1: Buat HEADER Penjualan (1 record)
            $penjualan = Penjualan::create([
                'nama_sales' => $validated['nama_sales'],
                'tanggal' => now(),
                'total' => 0, // Akan dihitung otomatis dari details
                'status' => $validated['status'] ?? 'pending', // 🔥 Default status
            ]);

            // 🔍 DEBUG 3: Log penjualan header yang dibuat
            Log::info('=== PENJUALAN HEADER DIBUAT ===');
            Log::info($penjualan->toArray());

            // ✅ STEP 2: Buat DETAILS (multiple records)
            $totalItems = count($validated['rincian']);
            $successCount = 0;

            for ($i = 0; $i < $totalItems; $i++) {
                $detail = PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id_penjualan,
                    'rincian' => $validated['rincian'][$i],
                    'jumlah' => $validated['jumlah'][$i],
                    'harga_satuan' => $validated['harga_satuan'][$i],
                    // subtotal akan dihitung otomatis di Model
                ]);

                // 🔍 DEBUG 4: Log setiap detail yang dibuat
                Log::info("=== DETAIL #{$i} DIBUAT ===");
                Log::info($detail->toArray());

                $successCount++;
            }

            // ✅ STEP 3: Update total penjualan
            // (Sebenarnya sudah otomatis di Model, tapi bisa dipanggil manual juga)
            $penjualan->updateTotal();

            // 🔥 STEP 4: BUAT NOTIFIKASI OTOMATIS
            // Refresh data penjualan untuk mendapatkan total terbaru
            $penjualan->refresh();
            
            // Buat notifikasi created
            Notification::createPenjualanNotification('created', $penjualan);
            
            // Notifikasi tambahan berdasarkan status
            if (($validated['status'] ?? 'pending') == 'pending') {
                Notification::createPenjualanNotification('pending', $penjualan);
            }

            DB::commit();

            // 🔍 DEBUG 5: Log sukses
            Log::info("=== BERHASIL MENYIMPAN 1 PENJUALAN DENGAN {$successCount} DETAILS ===");
            Log::info("=== TOTAL: {$penjualan->total} ===");

            return redirect()
                ->route('penjualan.index')
                ->with('success', "Berhasil menambahkan 1 penjualan dengan {$successCount} rincian dan notifikasi telah dibuat!");

        } catch (\Exception $e) {
            DB::rollBack();

            // 🔍 DEBUG 6: Log error
            Log::error('=== ERROR SAAT MENYIMPAN ===');
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Edit data - Load penjualan beserta details
     */
    public function edit($id_penjualan)
    {
        // Load penjualan dengan semua details
        $penjualan = Penjualan::with('details')->findOrFail($id_penjualan);
        
        return view('penjualan.edit', compact('penjualan'));
    }

    /**
     * ✅ Update data - Update HEADER dan DETAILS
     * NAMA SALES TIDAK BISA DIUBAH
     * 🔥 WITH NOTIFICATION SYSTEM
     */
    public function update(Request $request, $id_penjualan)
    {
        $penjualan = Penjualan::with('details')->findOrFail($id_penjualan);
        
        // Simpan status lama untuk deteksi perubahan
        $oldStatus = $penjualan->status ?? 'pending';
        $oldTotal = $penjualan->total;

        // ✅ Validasi input
        $validated = $request->validate([
            'rincian' => 'required|array|min:1',
            'rincian.*' => 'required|string|max:255',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|integer|min:1',
            'harga_satuan' => 'required|array|min:1',
            'harga_satuan.*' => 'required|numeric|min:0',
            'status' => 'nullable|string|in:pending,paid,shipped,completed,cancelled', // 🔥 TAMBAHAN
        ], [
            'rincian.required' => 'Rincian wajib diisi',
            'rincian.*.required' => 'Setiap rincian wajib diisi',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.*.required' => 'Setiap jumlah wajib diisi',
            'jumlah.*.min' => 'Jumlah minimal 1',
            'harga_satuan.required' => 'Harga satuan wajib diisi',
            'harga_satuan.*.required' => 'Setiap harga satuan wajib diisi',
            'harga_satuan.*.min' => 'Harga satuan minimal 0',
            'status.in' => 'Status tidak valid',
        ]);

        try {
            DB::beginTransaction();

            // ✅ STEP 1: Update status jika ada
            if (isset($validated['status'])) {
                $penjualan->status = $validated['status'];
                $penjualan->save();
            }

            // ✅ STEP 2: Hapus semua detail lama
            $penjualan->details()->delete();

            // ✅ STEP 3: Buat detail baru
            $totalItems = count($validated['rincian']);
            
            for ($i = 0; $i < $totalItems; $i++) {
                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id_penjualan,
                    'rincian' => $validated['rincian'][$i],
                    'jumlah' => $validated['jumlah'][$i],
                    'harga_satuan' => $validated['harga_satuan'][$i],
                ]);
            }

            // ✅ STEP 4: Update total
            $penjualan->updateTotal();
            
            // Refresh untuk mendapatkan data terbaru
            $penjualan->refresh();

            // 🔥 STEP 5: BUAT NOTIFIKASI JIKA STATUS BERUBAH
            $newStatus = $validated['status'] ?? $oldStatus;
            
            if ($oldStatus != $newStatus) {
                // Notifikasi spesifik berdasarkan status baru
                $notifType = match($newStatus) {
                    'pending' => 'pending',
                    'paid' => 'paid',
                    'shipped' => 'shipped',
                    'completed' => 'completed',
                    'cancelled' => 'cancelled',
                    default => 'updated'
                };
                
                Notification::createPenjualanNotification($notifType, $penjualan);
            } else {
                // Jika tidak ada perubahan status, buat notif update biasa
                Notification::createPenjualanNotification('updated', $penjualan);
            }

            DB::commit();

            return redirect()
                ->route('penjualan.index')
                ->with('success', 'Data berhasil diperbarui dan notifikasi telah dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Hapus data - Akan otomatis hapus details (cascade)
     * 🔥 WITH NOTIFICATION SYSTEM
     */
    public function destroy($id_penjualan)
    {
        $penjualan = Penjualan::findOrFail($id_penjualan);

        try {
            DB::beginTransaction();

            // 🔥 Simpan data penjualan sebelum dihapus untuk notifikasi
            $penjualanData = clone $penjualan;

            // Details akan terhapus otomatis karena onDelete('cascade') di migration
            $penjualan->delete();
            
            // 🔥 Buat notifikasi penghapusan
            Notification::createPenjualanNotification('deleted', $penjualanData);

            DB::commit();

            return redirect()
                ->route('penjualan.index')
                ->with('success', 'Data berhasil dihapus dan notifikasi telah dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * 🔥 UPDATE STATUS PENJUALAN (Method Tambahan)
     * Untuk update status secara terpisah tanpa mengubah detail
     */
    public function updateStatus(Request $request, $id_penjualan)
    {
        $penjualan = Penjualan::findOrFail($id_penjualan);
        
        // Simpan status lama
        $oldStatus = $penjualan->status ?? 'pending';
        
        $validated = $request->validate([
            'status' => 'required|string|in:pending,paid,shipped,completed,cancelled',
        ]);

        try {
            DB::beginTransaction();
            
            // Update status
            $penjualan->status = $validated['status'];
            $penjualan->save();
            
            // 🔥 Buat notifikasi perubahan status
            $notifType = match($validated['status']) {
                'pending' => 'pending',
                'paid' => 'paid',
                'shipped' => 'shipped',
                'completed' => 'completed',
                'cancelled' => 'cancelled',
                default => 'updated'
            };
            
            Notification::createPenjualanNotification($notifType, $penjualan->fresh());
            
            DB::commit();
            
            return redirect()
                ->back()
                ->with('success', 'Status penjualan berhasil diperbarui dan notifikasi telah dibuat.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }
}